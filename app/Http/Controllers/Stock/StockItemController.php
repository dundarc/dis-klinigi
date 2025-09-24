<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockCategory;
use App\Models\Stock\StockItem;
use App\Services\Stock\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

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
                ->when($status === 'critical', fn ($q) => $q->whereColumn('quantity', '<', 'minimum_quantity'))
                ->when($status === 'negative', fn ($q) => $q->where('quantity', '<', 0));
        }

        $items = $query->paginate(20)->withQueryString();
        $categories = StockCategory::orderBy('name')->get();

        return view('stock.items.index', [
            'items' => $items,
            'categories' => $categories,
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

            return redirect()->route('stock.items.index')->with('info', 'Stok kalemi kullanimda olduðundan pasif hale getirildi.');
        }

        $item->delete();

        return redirect()->route('stock.items.index')->with('success', 'Stok kalemi silindi.');
    }
}

