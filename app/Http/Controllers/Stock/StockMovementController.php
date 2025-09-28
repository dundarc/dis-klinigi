<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockItem;
use App\Models\Stock\StockMovement;
use App\Models\User;
use App\Services\Stock\StockMovementService;
use App\Enums\MovementDirection;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        return view('stock.movements.critical', compact('criticalItems', 'statistics'));
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
}