<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockExpense;
use App\Models\Stock\StockExpenseCategory;
use App\Models\Stock\StockItem;
use App\Models\Stock\StockSupplier;
use App\Services\OCRService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockExpenseController extends Controller
{
    public function __construct(private readonly OCRService $ocrService)
    {
    }

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
            'suppliers' => StockSupplier::services()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $mode = $request->input('mode', 'manual');

        $rules = [
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
            'mode' => ['required', Rule::in(['manual', 'upload'])],
            'add_to_stock' => ['nullable', 'boolean'],
        ];

        if ($mode === 'upload') {
            $rules['file'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:20480'];
        }

        $validated = $request->validate($rules);

        $expense = $mode === 'upload'
            ? $this->storeUploadedExpense($request, $validated)
            : $this->storeManualExpense($request, $validated);

        return redirect()->route('stock.expenses.index')->with('success', __('stock.expense_saved'));
    }

    public function edit(StockExpense $expense): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.expenses.edit', [
            'expense' => $expense,
            'categories' => StockExpenseCategory::orderBy('name')->get(),
            'suppliers' => StockSupplier::services()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, StockExpense $expense): RedirectResponse
    {
        $this->authorize('accessStockManagement');

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

        // Recalculate totals
        $validated['vat_amount'] = ($validated['amount'] * ($validated['vat_rate'] ?? 0) / 100);
        $validated['total_amount'] = $validated['amount'] + $validated['vat_amount'];

        $expense->update($validated);

        return redirect()->route('stock.expenses.index')->with('success', 'Gider kaydi guncellendi.');
    }

    public function show(StockExpense $expense): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.expenses.show', [
            'expense' => $expense->load(['category', 'supplier', 'payments']),
        ]);
    }

    public function destroy(StockExpense $expense): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $expense->delete();

        return redirect()->route('stock.expenses.index')->with('success', 'Gider kaydi silindi.');
    }

    protected function storeUploadedExpense(Request $request, array $validated): StockExpense
    {
        return DB::transaction(function () use ($request, $validated) {
            $ocrData = $this->ocrService->parseDocument($request->file('file'));

            $expenseData = [
                'category_id' => $validated['category_id'] ?? null,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'title' => $validated['title'],
                'expense_date' => $validated['expense_date'] ?? now()->toDateString(),
                'amount' => $validated['amount'],
                'vat_rate' => $validated['vat_rate'] ?? 0,
                'vat_amount' => ($validated['amount'] * ($validated['vat_rate'] ?? 0) / 100),
                'total_amount' => $validated['amount'] + (($validated['amount'] * ($validated['vat_rate'] ?? 0) / 100)),
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
                'paid_at' => $validated['paid_at'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'parsed_payload' => $ocrData,
            ];

            $expense = StockExpense::create($expenseData);

            // If add_to_stock is checked, create stock items
            if ($request->boolean('add_to_stock') && isset($ocrData['items'])) {
                $this->createStockItemsFromExpense($expense, $ocrData['items']);
            }

            return $expense;
        });
    }

    protected function storeManualExpense(Request $request, array $validated): StockExpense
    {
        $expenseData = [
            'category_id' => $validated['category_id'] ?? null,
            'supplier_id' => $validated['supplier_id'] ?? null,
            'title' => $validated['title'],
            'expense_date' => $validated['expense_date'] ?? now()->toDateString(),
            'amount' => $validated['amount'],
            'vat_rate' => $validated['vat_rate'] ?? 0,
            'vat_amount' => ($validated['amount'] * ($validated['vat_rate'] ?? 0) / 100),
            'total_amount' => $validated['amount'] + (($validated['amount'] * ($validated['vat_rate'] ?? 0) / 100)),
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'paid_at' => $validated['paid_at'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];

        $expense = StockExpense::create($expenseData);

        // If add_to_stock is checked, create a stock item from the expense
        if ($request->boolean('add_to_stock')) {
            $this->createStockItemFromExpense($expense);
        }

        return $expense;
    }

    protected function createStockItemsFromExpense(StockExpense $expense, array $items): void
    {
        foreach ($items as $itemData) {
            StockItem::create([
                'name' => $itemData['description'],
                'unit' => $itemData['unit'] ?? 'adet',
                'quantity' => $itemData['quantity'] ?? 0,
                'minimum_quantity' => 0,
                'allow_negative' => false,
                'is_active' => true,
            ]);
        }
    }

    protected function createStockItemFromExpense(StockExpense $expense): void
    {
        StockItem::create([
            'name' => $expense->title,
            'unit' => 'adet',
            'quantity' => 1,
            'minimum_quantity' => 0,
            'allow_negative' => false,
            'is_active' => true,
        ]);
    }

    public function updateDueDate(Request $request, StockExpense $expense): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $request->validate([
            'due_date' => ['nullable', 'date'],
        ]);

        $expense->update($validated);

        return redirect()->back()->with('success', 'Vade tarihi güncellendi.');
    }
}
