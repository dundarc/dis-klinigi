<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StockExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(StockExpenseCategory::class, 'stockExpenseCategory');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = StockExpenseCategory::withCount('expenses')
            ->orderBy('name')
            ->paginate(15);

        return view('stock.expense-categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stock.expense-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stock_expense_categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        StockExpenseCategory::create($validated);

        $redirectTo = $request->input('return_to') ?: route('stock.expense-categories.index');

        return redirect($redirectTo)
            ->with('success', 'Gider kategorisi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockExpenseCategory $stockExpenseCategory)
    {
        $expenses = $stockExpenseCategory->expenses()
            ->with('supplier')
            ->latest('expense_date')
            ->paginate(15);

        return view('stock.expense-categories.show', [
            'category' => $stockExpenseCategory,
            'expenses' => $expenses,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockExpenseCategory $stockExpenseCategory)
    {
        return view('stock.expense-categories.edit', [
            'category' => $stockExpenseCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockExpenseCategory $stockExpenseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stock_expense_categories,name,' . $stockExpenseCategory->id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $stockExpenseCategory->update($validated);

        return redirect()->route('stock.expense-categories.index')
            ->with('success', 'Gider kategorisi başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockExpenseCategory $stockExpenseCategory)
    {
        // Check if category has expenses
        if ($stockExpenseCategory->expenses()->count() > 0) {
            return redirect()->route('stock.expense-categories.index')
                ->with('error', 'Bu kategoriye ait giderler bulunduğu için silinemez.');
        }

        $stockExpenseCategory->delete();

        return redirect()->route('stock.expense-categories.index')
            ->with('success', 'Gider kategorisi başarıyla silindi.');
    }
}
