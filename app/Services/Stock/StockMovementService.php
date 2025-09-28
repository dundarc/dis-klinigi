<?php

namespace App\Services\Stock;

use App\Models\Stock\StockItem;
use App\Models\Stock\StockMovement;
use App\Enums\MovementDirection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Exception;

class StockMovementService
{
    /**
     * Record incoming stock movement (purchase, return, etc.)
     */
    public function recordIncoming(StockItem $item, float $quantity, array $attributes = []): StockMovement
    {
        return $this->recordMovement($item, MovementDirection::IN, $quantity, $attributes);
    }

    /**
     * Record outgoing stock movement (usage, sale, etc.)
     */
    public function recordOutgoing(StockItem $item, float $quantity, array $attributes = []): StockMovement
    {
        return $this->recordMovement($item, MovementDirection::OUT, $quantity, $attributes);
    }

    /**
     * Record stock adjustment (correction, inventory count, etc.)
     */
    public function recordAdjustment(StockItem $item, float $newQuantity, array $attributes = []): StockMovement
    {
        $currentQuantity = (float) $item->quantity;
        $adjustmentQuantity = $newQuantity - $currentQuantity;
        
        if ($adjustmentQuantity == 0) {
            throw new InvalidArgumentException('No adjustment needed - quantities are the same.');
        }

        return $this->recordMovement($item, MovementDirection::ADJUSTMENT, abs($adjustmentQuantity), array_merge($attributes, [
            'note' => ($attributes['note'] ?? '') . " (${currentQuantity} -> ${newQuantity})"
        ]));
    }

    /**
     * Check if item can handle the outgoing quantity without going negative
     */
    public function canProcessOutgoing(StockItem $item, float $quantity): bool
    {
        if ($item->allow_negative) {
            return true;
        }

        return (float) $item->quantity >= $quantity;
    }

    /**
     * Get items that are below minimum quantity
     */
    public function getCriticalStockItems(): \Illuminate\Database\Eloquent\Collection
    {
        return StockItem::where('is_active', true)
            ->where('minimum_quantity', '>', 0)
            ->whereRaw('quantity < minimum_quantity')
            ->with(['category', 'movements' => function($query) {
                $query->latest()->limit(5);
            }])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get stock movement history with filters
     */
    public function getMovementHistory(array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = StockMovement::with(['stockItem.category', 'creator', 'reference'])
            ->latest('movement_date')
            ->latest('created_at');

        if (!empty($filters['direction'])) {
            $query->byDirection(MovementDirection::from($filters['direction']));
        }

        if (!empty($filters['item_id'])) {
            $query->byItem($filters['item_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->byUser($filters['user_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->byDateRange($filters['start_date'], $filters['end_date']);
        }

        if (!empty($filters['reference_type'])) {
            $query->where('reference_type', $filters['reference_type']);
        }

        return $query;
    }

    /**
     * Get stock statistics for dashboard
     */
    public function getStockStatistics(): array
    {
        $criticalItems = $this->getCriticalStockItems();
        $totalItems = StockItem::where('is_active', true)->count();
        $recentMovements = StockMovement::recent(7)->count();
        $lowStockCount = $criticalItems->count();

        return [
            'total_active_items' => $totalItems,
            'critical_stock_count' => $lowStockCount,
            'recent_movements_count' => $recentMovements,
            'critical_stock_percentage' => $totalItems > 0 ? round(($lowStockCount / $totalItems) * 100, 1) : 0,
            'critical_items' => $criticalItems->take(5),
        ];
    }

    /**
     * Bulk process movements for multiple items (useful for invoice processing)
     */
    public function bulkProcessMovements(array $movements): array
    {
        $results = [];
        $errors = [];

        DB::beginTransaction();
        
        try {
            foreach ($movements as $index => $movementData) {
                try {
                    $item = StockItem::findOrFail($movementData['stock_item_id']);
                    $direction = MovementDirection::from($movementData['direction']);
                    $quantity = (float) $movementData['quantity'];
                    $attributes = $movementData['attributes'] ?? [];

                    $movement = $this->recordMovement($item, $direction, $quantity, $attributes);
                    $results[] = $movement;
                } catch (Exception $e) {
                    $errors[] = [
                        'index' => $index,
                        'error' => $e->getMessage(),
                        'data' => $movementData
                    ];
                }
            }

            if (empty($errors)) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'success' => empty($errors),
            'processed' => count($results),
            'results' => $results,
            'errors' => $errors
        ];
    }

    /**
     * Core method to record any stock movement
     */
    protected function recordMovement(StockItem $item, MovementDirection $direction, float $quantity, array $attributes): StockMovement
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($item, $direction, $quantity, $attributes) {
            // Check negative stock constraint for outgoing movements
            if ($direction === MovementDirection::OUT && !$this->canProcessOutgoing($item, $quantity)) {
                throw new InvalidArgumentException(
                    "Insufficient stock for item '{$item->name}'. Available: {$item->quantity}, Required: {$quantity}"
                );
            }

            // Create movement record
            $movement = new StockMovement(array_merge($attributes, [
                'direction' => $direction,
                'quantity' => $quantity,
                'movement_date' => $attributes['movement_date'] ?? now(),
                'created_by' => $attributes['created_by'] ?? auth()->id(),
            ]));
            
            $movement->stockItem()->associate($item);
            $movement->save();

            // Update item quantity
            $newQuantity = $this->calculateNewQuantity($item, $direction, $quantity);
            $item->forceFill(['quantity' => $newQuantity])->save();

            // Log the movement
            Log::info('Stock movement recorded', [
                'item_id' => $item->id,
                'item_name' => $item->name,
                'direction' => $direction->value,
                'quantity' => $quantity,
                'old_stock' => $item->getOriginal('quantity'),
                'new_stock' => $newQuantity,
                'user_id' => $movement->created_by,
                'reference' => $movement->reference_type ? class_basename($movement->reference_type) . '#' . $movement->reference_id : null
            ]);

            // Check if item is now critical
            if ($item->isBelowMinimum()) {
                Log::warning('Stock item below minimum', [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'current_quantity' => $newQuantity,
                    'minimum_quantity' => $item->minimum_quantity
                ]);
            }

            return $movement;
        });
    }

    /**
     * Calculate new quantity based on direction
     */
    protected function calculateNewQuantity(StockItem $item, MovementDirection $direction, float $quantity): float
    {
        $current = (float) $item->quantity;

        return match ($direction) {
            MovementDirection::IN => $current + $quantity,
            MovementDirection::OUT => $current - $quantity,
            MovementDirection::ADJUSTMENT => $quantity, // For adjustments, quantity is the new absolute value
        };
    }
}
