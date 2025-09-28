<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('accessStockManagement');

        $query = StockCategory::query();

        if ($search = $request->string('search')->toString()) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        return view('stock.categories.index', [
            'categories' => $query->orderBy('name')->paginate(15)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:stock_categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        StockCategory::create($validated);

        return redirect()->route('stock.categories.index')
                        ->with('success', __('stock.category_created'));
    }

    public function show(StockCategory $category): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.categories.show', [
            'category' => $category->load('items'),
        ]);
    }

    public function edit(StockCategory $category): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, StockCategory $category): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:stock_categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $category->update($validated);

        return redirect()->route('stock.categories.show', $category)
                        ->with('success', __('stock.category_updated'));
    }

    public function destroy(StockCategory $category): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        if (!$category->canBeDeleted()) {
            return redirect()->route('stock.categories.index')
                            ->with('error', __('stock.cannot_delete_medical_supplies'));
        }

        if ($category->items()->exists()) {
            return redirect()->route('stock.categories.index')
                            ->with('error', 'Bu kategoriye ait malzemeler bulunduğu için silinemez.');
        }

        $category->delete();

        return redirect()->route('stock.categories.index')
                        ->with('success', __('stock.category_deleted'));
    }
}
