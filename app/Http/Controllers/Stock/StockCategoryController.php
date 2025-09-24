<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StockCategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('accessStockManagement');

        $categories = StockCategory::withCount('items')->orderBy('name')->get();

        return view('stock.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        StockCategory::create($validated);

        return request()->filled('return_to')
            ? redirect(request()->input('return_to'))->with('success', 'Kategori eklendi.')
            : redirect()->route('stock.categories.index')->with('success', 'Kategori eklendi.');
    }

    public function update(Request $request, StockCategory $category): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update(array_merge($validated, [
            'slug' => Str::slug($validated['name']),
        ]));

        return redirect()->route('stock.categories.index')->with('success', 'Kategori guncellendi.');
    }

    public function destroy(StockCategory $category): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        if ($category->items()->exists()) {
            return redirect()->route('stock.categories.index')->with('error', 'Kategoriye ba�l� stok kalemleri bulundugu i�in silinemez.');
        }

        $category->delete();

        return redirect()->route('stock.categories.index')->with('success', 'Kategori silindi.');
    }
}


