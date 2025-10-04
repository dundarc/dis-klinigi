<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockItem;
use App\Models\Stock\StockMovement;
use App\Models\User;
use App\Services\Stock\StockMovementService;
use App\Enums\MovementDirection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class StockMovementController extends Controller
{
    public function __construct(private readonly StockMovementService $movementService)
    {
    }

    /**
     * Display stock movement history with filters
     */
    public function index(Request $request): View
    {
        $this->authorize('accessStockManagement');

        $filters = $request->only(['direction', 'item_id', 'user_id', 'start_date', 'end_date', 'reference_type']);
        
        $movements = $this->movementService->getMovementHistory($filters)
            ->paginate(20)
            ->withQueryString();

        $stockItems = StockItem::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $users = User::whereHas('stockMovements')
            ->orderBy('name')
            ->get(['id', 'name']);

        $directions = MovementDirection::cases();

        return view('stock.movements.index', compact(
            'movements',
            'stockItems', 
            'users',
            'directions',
            'filters'
        ));
    }

    /**
     * Show critical stock items
     */
    public function critical(): View
    {
        $this->authorize('accessStockManagement');

        $criticalItems = $this->movementService->getCriticalStockItems();
        $statistics = $this->movementService->getStockStatistics();
        $categories = \App\Models\Stock\StockCategory::orderBy('name')->get();

        // Calculate total value of critical items (assuming we have unit prices, but for now just count)
        $totalValue = $criticalItems->sum(function ($item) {
            // This is a placeholder - you might want to calculate based on unit price * quantity
            // For now, just return the count or a default value
            return 0; // Replace with actual calculation when you have pricing
        });

        return view('stock.movements.critical', compact('criticalItems', 'statistics', 'totalValue', 'categories'));
    }

    /**
     * Display form for manual stock adjustment
     */
    public function createAdjustment(): View
    {
        $this->authorize('accessStockManagement');

        $stockItems = StockItem::where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('stock.movements.create-adjustment', compact('stockItems'));
    }

    /**
     * Process manual stock adjustment
     */
    public function storeAdjustment(Request $request)
    {
        $this->authorize('accessStockManagement');

        $validated = $request->validate([
            'stock_item_id' => 'required|exists:stock_items,id',
            'new_quantity' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            $stockItem = StockItem::findOrFail($validated['stock_item_id']);
            
            $movement = $this->movementService->recordAdjustment(
                $stockItem,
                (float) $validated['new_quantity'],
                [
                    'note' => "Manual düzeltme - Sebep: {$validated['reason']}" . 
                             ($validated['notes'] ? " - Not: {$validated['notes']}" : ''),
                    'created_by' => auth()->id(),
                    'movement_date' => now(),
                ]
            );

            return redirect()
                ->route('stock.movements.index')
                ->with('success', 'Stok düzeltmesi başarıyla kaydedildi.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Stok düzeltmesi yapılırken hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Show detailed movement history for specific item
     */
    public function showItemHistory(StockItem $item): View
    {
        $this->authorize('accessStockManagement');

        $movements = StockMovement::byItem($item->id)
            ->with(['creator', 'reference'])
            ->latest('movement_date')
            ->latest('created_at')
            ->paginate(50);

        $statistics = [
            'total_movements' => $movements->total(),
            'total_incoming' => StockMovement::byItem($item->id)->incoming()->sum('quantity'),
            'total_outgoing' => StockMovement::byItem($item->id)->outgoing()->sum('quantity'),
            'recent_movements' => StockMovement::byItem($item->id)->recent(30)->count(),
        ];

        return view('stock.movements.item-history', compact('item', 'movements', 'statistics'));
    }

    /**
     * API endpoint for stock movement statistics
     */
    public function getStatistics()
    {
        $this->authorize('accessStockManagement');

        return response()->json($this->movementService->getStockStatistics());
    }

    /**
     * API endpoint for item suggestions with current stock
     */
    public function getItemsWithStock(Request $request)
    {
        $this->authorize('accessStockManagement');

        $search = $request->input('search', '');
        
        $items = StockItem::where('is_active', true)
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'sku', 'quantity', 'minimum_quantity', 'unit']);

        return response()->json($items->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'current_stock' => $item->quantity,
                'minimum_stock' => $item->minimum_quantity,
                'unit' => $item->unit,
                'is_critical' => $item->isBelowMinimum(),
                'display_name' => $item->display_name,
            ];
        }));
    }

    /**
     * Export movements to PDF
     */
    public function exportPdf(Request $request)
    {
        $this->authorize('accessStockManagement');

        $filters = $request->only(['direction', 'item_id', 'user_id', 'start_date', 'end_date', 'reference_type']);

        $movements = $this->movementService->getMovementHistory($filters)
            ->with(['stockItem.category', 'creator', 'reference'])
            ->get();

        $pdf = Pdf::loadView('stock.movements.pdf', compact('movements', 'filters'));

        $filename = 'stok-hareketleri-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Print movements
     */
    public function print(Request $request)
    {
        $this->authorize('accessStockManagement');

        $filters = $request->only(['direction', 'item_id', 'user_id', 'start_date', 'end_date', 'reference_type']);

        $movements = $this->movementService->getMovementHistory($filters)
            ->with(['stockItem.category', 'creator', 'reference'])
            ->get();

        return view('stock.movements.print', compact('movements', 'filters'));
    }

    /**
     * Export recent movements to PDF with time filter
     */
    public function exportRecentMovementsPdf(Request $request)
    {
        $this->authorize('accessStockManagement');

        $filter = $request->get('filter', '24h');

        // Calculate date range based on filter
        $startDate = match($filter) {
            '24h' => now()->subDay(),
            '1w' => now()->subWeek(),
            '1m' => now()->subMonth(),
            '6m' => now()->subMonths(6),
            '1y' => now()->subYear(),
            default => now()->subDay(),
        };

        $movements = StockMovement::with(['stockItem.category', 'creator', 'reference'])
            ->where('created_at', '>=', $startDate)
            ->latest('created_at')
            ->get();

        $filterLabel = match($filter) {
            '24h' => 'Son 24 Saat',
            '1w' => 'Son 1 Hafta',
            '1m' => 'Son 1 Ay',
            '6m' => 'Son 6 Ay',
            '1y' => 'Son 1 Yıl',
            default => 'Son 24 Saat',
        };

        $pdf = Pdf::loadView('stock.movements.recent-pdf', compact('movements', 'filterLabel'));

        $filename = 'son-hareketler-' . $filter . '-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get recent bulk operations for display
     */
    public function getRecentBulkOperations(Request $request)
    {
        $this->authorize('accessStockManagement');

        $perPage = 5; // As requested by user

        $bulkOperations = StockMovement::selectRaw('batch_id, COUNT(*) as movement_count, MIN(created_at) as batch_created_at, MAX(created_at) as batch_updated_at')
            ->selectRaw('GROUP_CONCAT(DISTINCT stock_item_id) as item_ids')
            ->whereNotNull('batch_id')
            ->where('batch_id', 'like', 'bulk_%')
            ->groupBy('batch_id')
            ->orderBy('batch_created_at', 'desc')
            ->paginate($perPage);

        // Get detailed information for each batch
        $bulkOperations->getCollection()->transform(function ($batch) {
            $firstMovement = StockMovement::where('batch_id', $batch->batch_id)
                ->with(['creator', 'stockItem'])
                ->orderBy('created_at')
                ->first();

            $batch->creator = $firstMovement?->creator;
            $batch->total_quantity = StockMovement::where('batch_id', $batch->batch_id)->sum('quantity');
            $batch->items = StockMovement::where('batch_id', $batch->batch_id)
                ->with('stockItem')
                ->get()
                ->map(function ($movement) {
                    return [
                        'name' => $movement->stockItem->name,
                        'direction' => $movement->direction->label(),
                        'quantity' => $movement->quantity,
                    ];
                });

            return $batch;
        });

        return response()->json($bulkOperations);
    }

    /**
     * Export individual bulk operation to PDF
     */
    public function exportBulkOperationPdf($batchId)
    {
        $this->authorize('accessStockManagement');

        // Get all movements for this batch
        $movements = StockMovement::where('batch_id', $batchId)
            ->with(['stockItem.category', 'creator', 'reference'])
            ->orderBy('created_at')
            ->get();

        if ($movements->isEmpty()) {
            abort(404, 'Bulk operation not found');
        }

        // Get batch information
        $firstMovement = $movements->first();
        $batchInfo = [
            'batch_id' => $batchId,
            'created_at' => $firstMovement->created_at,
            'creator' => $firstMovement->creator,
            'total_movements' => $movements->count(),
            'total_quantity' => $movements->sum('quantity'),
        ];

        $pdf = Pdf::loadView('stock.movements.bulk-pdf', compact('movements', 'batchInfo'));

        $filename = 'toplu-islem-' . $batchId . '-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }
    /**
     * Show bulk movements form
     */
    public function bulkMovements(): View
    {
        $this->authorize('accessStockManagement');

        $stockItems = StockItem::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'quantity', 'unit']);

        return view('stock.movements.bulk', compact('stockItems'));
    }

    /**
     * Store bulk movements via AJAX
     */
    public function storeBulkMovements(Request $request)
    {
        $this->authorize('accessStockManagement');

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:stock_items,id',
            'items.*.direction' => 'required|in:in,out,adjustment',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.note' => 'nullable|string|max:255',
            'confirm_irreversible' => 'required|accepted',
        ]);

        $results = [];
        $errors = [];
        $warnings = [];

        DB::beginTransaction();

        try {
            // Generate a batch ID for this bulk operation
            $batchId = 'bulk_' . now()->format('Ymd_His') . '_' . auth()->id();

            foreach ($request->items as $index => $itemData) {
                try {
                    $item = StockItem::findOrFail($itemData['item_id']);
                    $direction = $itemData['direction'];
                    $quantity = (float) $itemData['quantity'];

                    if ($direction === 'in') {
                        $movement = $this->movementService->recordIncoming($item, $quantity, [
                            'note' => $itemData['note'] ?: 'Toplu giriş',
                            'created_by' => $request->user()->id,
                            'batch_id' => $batchId,
                        ]);
                    } elseif ($direction === 'out') {
                        $movement = $this->movementService->recordOutgoing($item, $quantity, [
                            'note' => $itemData['note'] ?: 'Toplu çıkış',
                            'created_by' => $request->user()->id,
                            'batch_id' => $batchId,
                        ]);
                    } elseif ($direction === 'adjustment') {
                        $movement = $this->movementService->recordAdjustment($item, $quantity, [
                            'note' => $itemData['note'] ?: 'Toplu düzeltme',
                            'created_by' => $request->user()->id,
                            'batch_id' => $batchId,
                        ]);
                    }

                    $updatedItem = $item->fresh();

                    // Check for warnings
                    if ($updatedItem->quantity < 0) {
                        $warnings[] = [
                            'item' => $item->name,
                            'type' => 'negative_stock',
                            'message' => 'Stok negatif seviyeye düştü: ' . $updatedItem->quantity,
                        ];
                    } elseif ($updatedItem->isBelowMinimum()) {
                        $warnings[] = [
                            'item' => $item->name,
                            'type' => 'critical_stock',
                            'message' => 'Stok kritik seviyeye düştü: ' . $updatedItem->quantity . ' (Min: ' . $updatedItem->minimum_quantity . ')',
                        ];
                    }

                    $results[] = [
                        'item' => $item->name,
                        'direction' => $direction,
                        'quantity' => $quantity,
                        'new_stock' => $updatedItem->quantity,
                        'batch_id' => $batchId,
                    ];
                } catch (\Exception $e) {
                    $errors[] = [
                        'index' => $index,
                        'item' => $itemData['item_id'],
                        'error' => $e->getMessage(),
                    ];
                }
            }

            if (empty($errors)) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => count($results) . ' hareket başarıyla işlendi.',
                    'results' => $results,
                    'warnings' => $warnings,
                    'batch_id' => $batchId,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Bazı işlemler başarısız oldu.',
                    'errors' => $errors,
                    'processed' => count($results),
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'İşlem sırasında hata oluştu: ' . $e->getMessage(),
            ], 500);
        }
    }
}