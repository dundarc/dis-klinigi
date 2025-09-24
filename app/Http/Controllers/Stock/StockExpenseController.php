<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockExpense;
use App\Models\Stock\StockExpenseCategory;
use App\Models\Stock\StockSupplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('accessStockManagement');

        $query = StockExpense::with(['category', 'supplier'])->latest('expense_date')->latest();

        if ($category = $request->input('category_id')) {
            $query->where('category_id', $category);
        }

        if ($supplier = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplier);
        }

        if ($status = $request->string('payment_status')->toString()) {
            $query->where('payment_status', $status);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('expense_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('expense_date', '<=', $to);
        }

        return view('stock.expenses.index', [
            'expenses' => $query->paginate(15)->withQueryString(),
            'categories' => StockExpenseCategory::orderBy('name')->get(),
            'suppliers' => StockSupplier::orderBy('name')->get(),
            'filters' => $request->only(['category_id', 'supplier_id', 'payment_status', 'date_from', 'date_to']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.expenses.create', [
            'categories' => StockExpenseCategory::orderBy('name')->get(),
            'suppliers' => StockSupplier::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $data = $this->validateExpense($request);

        $expense = StockExpense::create($data);

        return redirect()->route('stock.expenses.index')->with('success', 'Gider kaydi olusturuldu.');
    }

    public function edit(StockExpense $expense): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.expenses.edit', [
            'expense' => $expense,
            'categories' => StockExpenseCategory::orderBy('name')->get(),
            'suppliers' => StockSupplier::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, StockExpense $expense): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $expense->update($this->validateExpense($request));

        return redirect()->route('stock.expenses.index')->with('success', 'Gider kaydi guncellendi.');
    }

    public function destroy(StockExpense $expense): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $expense->delete();

        return redirect()->route('stock.expenses.index')->with('success', 'Gider kaydi silindi.');
    }

    protected function validateExpense(Request $request): array
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:stock_expense_categories,id'],
            'supplier_id' => ['nullable', 'exists:stock_suppliers,id'],
            'title' => ['required', 'string', 'max:255'],
            'expense_date' => ['nullable', 'date'],
            'amount' => ['required', 'numeric', 'gte:0'],
            'vat_rate' => ['nullable', 'numeric', 'gte:0'],
            'payment_status' => ['required', Rule::in(['pending', 'partial', 'paid', 'overdue'])],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'due_date' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $vatRate = isset($validated['vat_rate']) ? (float) $validated['vat_rate'] : 0;
        $amount = (float) $validated['amount'];
        $vatAmount = $amount * ($vatRate / 100);
        $totalAmount = $amount + $vatAmount;

        return array_merge($validated, [
            'expense_date' => $validated['expense_date'] ?? now()->toDateString(),
            'vat_amount' => $vatAmount,
            'total_amount' => $totalAmount,
        ]);
    }
}
