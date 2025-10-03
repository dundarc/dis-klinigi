<?php

namespace App\Http\Controllers\Stock;

use App\Exports\StockItemsExport;
use App\Http\Controllers\Controller;
use App\Models\Stock\StockCategory;
use App\Models\Stock\StockItem;
use App\Services\Stock\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class StockItemController extends Controller
{
    public function __construct(private readonly StockMovementService $movementService)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('accessStockManagement');

        $query = StockItem::with('category')->orderBy('name');

        if ($search = $request->string('q')->toString()) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('status')) {
            $status = $request->string('status')->toString();
            $query->when($status === 'active', fn ($q) => $q->where('is_active', true))
                ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
                ->when($status === 'critical', fn ($q) => $q->where('minimum_quantity', '>', 0)->whereColumn('quantity', '<', 'minimum_quantity'))
                ->when($status === 'low', fn ($q) => $q->where('minimum_quantity', '>', 0)->whereRaw('quantity <= minimum_quantity * 1.5 AND quantity >= minimum_quantity'))
                ->when($status === 'sufficient', fn ($q) => $q->where(function($subQ) {
                    $subQ->where('minimum_quantity', '<=', 0)
                         ->orWhereRaw('quantity > minimum_quantity * 1.5');
                }))
                ->when($status === 'negative', fn ($q) => $q->where('quantity', '<', 0));
        }

        $items = $query->paginate(20)->withQueryString();
        $categories = StockCategory::orderBy('name')->get();
        
        // Calculate statistics for dashboard widgets
        $statistics = [
            'total_items' => StockItem::count(),
            'active_items' => StockItem::where('is_active', true)->count(),
            'critical_items' => StockItem::where('is_active', true)
                                        ->where('minimum_quantity', '>', 0)
                                        ->whereColumn('quantity', '<', 'minimum_quantity')
                                        ->count(),
            'low_stock_items' => StockItem::where('is_active', true)
                                         ->where('minimum_quantity', '>', 0)
                                         ->whereRaw('quantity <= minimum_quantity * 1.5')
                                         ->whereRaw('quantity >= minimum_quantity')
                                         ->count(),
            'negative_stock_items' => StockItem::where('is_active', true)
                                              ->where('quantity', '<', 0)
                                              ->count(),
        ];

        return view('stock.items.index', [
            'items' => $items,
            'categories' => $categories,
            'statistics' => $statistics,
            'filters' => [
                'q' => $search,
                'category' => $request->input('category'),
                'status' => $request->input('status'),
            ],
        ]);
    }

    public function create(): View
    {
        $this->authorize('accessStockManagement');

        $categories = StockCategory::orderBy('name')->get();

        return view('stock.items.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:120', 'unique:stock_items,sku'],
            'barcode' => ['nullable', 'string', 'max:120', 'unique:stock_items,barcode'],
            'category_id' => ['nullable', 'exists:stock_categories,id'],
            'unit' => ['nullable', 'string', 'max:32'],
            'minimum_quantity' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'numeric'],
            'allow_negative' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $initialQuantity = (float) ($validated['quantity'] ?? 0);
        unset($validated['quantity']);

        $item = StockItem::create(array_merge($validated, [
            'quantity' => 0,
            'minimum_quantity' => $validated['minimum_quantity'] ?? 0,
            'unit' => $validated['unit'] ?? 'adet',
            'allow_negative' => $request->boolean('allow_negative'),
            'is_active' => $request->boolean('is_active', true),
        ]));

        if ($initialQuantity > 0) {
            $this->movementService->recordAdjustment($item->fresh(), $initialQuantity, [
                'reference_type' => self::class,
                'reference_id' => $item->id,
                'note' => 'Baslangic stogu',
                'created_by' => $request->user()->id,
            ]);
        }

        return redirect()->route('stock.items.index')->with('success', 'Stok kalemi olusturuldu.');
    }

    public function show(StockItem $item): View
    {
        $this->authorize('accessStockManagement');

        $movements = $item->movements()->with(['creator'])->latest()->paginate(20);

        return view('stock.items.show', compact('item', 'movements'));
    }

    public function addMovement(Request $request, StockItem $item)
    {
        $this->authorize('accessStockManagement');

        $request->validate([
            'direction' => 'required|in:in,out,adjustment',
            'quantity' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        $direction = $request->direction;
        $quantity = (float) $request->quantity;

        try {
            if ($direction === 'in') {
                $this->movementService->recordIncoming($item, $quantity, [
                    'note' => $request->note ?: 'Manuel giriş',
                    'created_by' => $request->user()->id,
                ]);
            } elseif ($direction === 'out') {
                $this->movementService->recordOutgoing($item, $quantity, [
                    'note' => $request->note ?: 'Manuel çıkış',
                    'created_by' => $request->user()->id,
                ]);
            } elseif ($direction === 'adjustment') {
                $this->movementService->recordAdjustment($item, $quantity, [
                    'note' => $request->note ?: 'Stok düzeltmesi',
                    'created_by' => $request->user()->id,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Hareket başarıyla eklendi.',
            'new_quantity' => $item->fresh()->quantity,
        ]);
    }

    public function bulkMovement(Request $request)
    {
        $this->authorize('accessStockManagement');

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:stock_items,id',
            'items.*.direction' => 'required|in:in,out,adjustment',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.note' => 'nullable|string|max:255',
        ]);

        $results = [];
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($request->items as $index => $itemData) {
                try {
                    $item = StockItem::findOrFail($itemData['item_id']);
                    $direction = $itemData['direction'];
                    $quantity = (float) $itemData['quantity'];

                    if ($direction === 'in') {
                        $movement = $this->movementService->recordIncoming($item, $quantity, [
                            'note' => $itemData['note'] ?: 'Toplu giriş',
                            'created_by' => $request->user()->id,
                        ]);
                    } elseif ($direction === 'out') {
                        $movement = $this->movementService->recordOutgoing($item, $quantity, [
                            'note' => $itemData['note'] ?: 'Toplu çıkış',
                            'created_by' => $request->user()->id,
                        ]);
                    } elseif ($direction === 'adjustment') {
                        $movement = $this->movementService->recordAdjustment($item, $quantity, [
                            'note' => $itemData['note'] ?: 'Toplu düzeltme',
                            'created_by' => $request->user()->id,
                        ]);
                    }

                    $results[] = [
                        'item' => $item->name,
                        'direction' => $direction,
                        'quantity' => $quantity,
                        'new_stock' => $item->fresh()->quantity,
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

    public function edit(StockItem $item): View
    {
        $this->authorize('accessStockManagement');

        $categories = StockCategory::orderBy('name')->get();

        return view('stock.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, StockItem $item): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:120', Rule::unique('stock_items', 'sku')->ignore($item->id)],
            'barcode' => ['nullable', 'string', 'max:120', Rule::unique('stock_items', 'barcode')->ignore($item->id)],
            'category_id' => ['nullable', 'exists:stock_categories,id'],
            'unit' => ['nullable', 'string', 'max:32'],
            'minimum_quantity' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'numeric'],
            'allow_negative' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $newQuantity = $validated['quantity'] ?? null;
        unset($validated['quantity']);

        $item->update(array_merge($validated, [
            'allow_negative' => $request->boolean('allow_negative'),
            'is_active' => $request->boolean('is_active', true),
        ]));

        if ($newQuantity !== null && (float) $newQuantity !== (float) $item->quantity) {
            $this->movementService->recordAdjustment($item->fresh(), (float) $newQuantity, [
                'reference_type' => self::class,
                'reference_id' => $item->id,
                'note' => 'Stok duzeltmesi',
                'created_by' => $request->user()->id,
            ]);
        }

        return redirect()->route('stock.items.index')->with('success', 'Stok kalemi guncellendi.');
    }

    public function destroy(StockItem $item): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        if ($item->movements()->exists()) {
            $item->forceFill(['is_active' => false])->save();

            return redirect()->route('stock.items.index')->with('info', 'Stok kalemi kullanimda olduğundan pasif hale getirildi.');
        }

        $item->delete();

        return redirect()->route('stock.items.index')->with('success', 'Stok kalemi silindi.');
    }

    public function search(Request $request)
    {
        $this->authorize('accessStockManagement');

        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $items = StockItem::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'unit', 'sku']);

        return response()->json($items);
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('accessStockManagement');

        $query = $request->get('q', '');
        $filename = 'stok_kalemleri_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new StockItemsExport($query), $filename);
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('accessStockManagement');

        $query = StockItem::with('category')->orderBy('name');

        if ($search = $request->string('q')->toString()) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('status')) {
            $status = $request->string('status')->toString();
            $query->when($status === 'active', fn ($q) => $q->where('is_active', true))
                ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
                ->when($status === 'critical', fn ($q) => $q->where('minimum_quantity', '>', 0)->whereColumn('quantity', '<', 'minimum_quantity'))
                ->when($status === 'low', fn ($q) => $q->where('minimum_quantity', '>', 0)->whereRaw('quantity <= minimum_quantity * 1.5 AND quantity >= minimum_quantity'))
                ->when($status === 'sufficient', fn ($q) => $q->where(function($subQ) {
                    $subQ->where('minimum_quantity', '<=', 0)
                         ->orWhereRaw('quantity > minimum_quantity * 1.5');
                }))
                ->when($status === 'negative', fn ($q) => $q->where('quantity', '<', 0));
        }

        $items = $query->get();

        $pdf = Pdf::loadView('stock.items.pdf', [
            'items' => $items,
            'title' => 'Stok Kalemleri Raporu',
            'generated_at' => now()->format('d.m.Y H:i'),
            'filters' => [
                'q' => $request->input('q'),
                'category' => $request->input('category'),
                'status' => $request->input('status'),
            ],
        ]);

        $filename = 'stok_kalemleri_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    public function print(Request $request)
    {
        $this->authorize('accessStockManagement');

        $query = StockItem::with('category')->orderBy('name');

        if ($search = $request->string('q')->toString()) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('status')) {
            $status = $request->string('status')->toString();
            $query->when($status === 'active', fn ($q) => $q->where('is_active', true))
                ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
                ->when($status === 'critical', fn ($q) => $q->where('minimum_quantity', '>', 0)->whereColumn('quantity', '<', 'minimum_quantity'))
                ->when($status === 'low', fn ($q) => $q->where('minimum_quantity', '>', 0)->whereRaw('quantity <= minimum_quantity * 1.5 AND quantity >= minimum_quantity'))
                ->when($status === 'sufficient', fn ($q) => $q->where(function($subQ) {
                    $subQ->where('minimum_quantity', '<=', 0)
                         ->orWhereRaw('quantity > minimum_quantity * 1.5');
                }))
                ->when($status === 'negative', fn ($q) => $q->where('quantity', '<', 0));
        }

        $items = $query->get();

        return view('stock.items.print', [
            'items' => $items,
            'title' => 'Stok Kalemleri Raporu',
            'generated_at' => now()->format('d.m.Y H:i'),
            'filters' => [
                'q' => $request->input('q'),
                'category' => $request->input('category'),
                'status' => $request->input('status'),
            ],
        ]);
    }
}

