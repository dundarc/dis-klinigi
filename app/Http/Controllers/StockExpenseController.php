<?php

namespace App\Http\Controllers;

use App\Models\StockExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockExpenseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(StockExpense::class, 'stockExpense');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockExpense::with(['category', 'supplier']);

        // Filters
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = $request->input('status')) {
            $query->where('payment_status', $status);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('expense_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('expense_date', '<=', $to);
        }

        $expenses = $query->latest('expense_date')->paginate(15)->withQueryString();

        $categories = \App\Models\Stock\StockExpenseCategory::all();

        return view('stock.expenses.index', [
            'expenses' => $expenses,
            'categories' => $categories,
            'filters' => $request->only(['category_id', 'status', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Stock\StockExpenseCategory::all();
        $suppliers = \App\Models\Stock\StockSupplier::all();

        return view('stock.expenses.create', [
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:stock_expense_categories,id',
            'supplier_id' => 'nullable|exists:stock_suppliers,id',
            'amount' => 'required|numeric|min:0',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'payment_status' => 'required|in:paid,pending,overdue',
            'due_date' => 'nullable|date|after_or_equal:expense_date',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Calculate VAT amounts
        $vatRate = $validated['vat_rate'] ?? 0;
        $amount = $validated['amount'];
        $vatAmount = ($amount * $vatRate) / 100;
        $totalAmount = $amount + $vatAmount;

        $validated['vat_amount'] = $vatAmount;
        $validated['total_amount'] = $totalAmount;

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('stock/expenses', 'public');
        }

        $expense = StockExpense::create($validated);

        // Create account movement if paid
        if ($validated['payment_status'] === 'paid') {
            \App\Models\Stock\StockAccountMovement::create([
                'reference_type' => StockExpense::class,
                'reference_id' => $expense->id,
                'type' => 'expense',
                'amount' => $totalAmount,
                'description' => $validated['title'],
                'movement_date' => $validated['expense_date'],
            ]);
        }

        return redirect()->route('stock.expenses.index')->with('success', 'Gider başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockExpense $stockExpense)
    {
        return view('stock.expenses.show', [
            'expense' => $stockExpense->load(['category', 'supplier', 'accountMovements']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockExpense $stockExpense)
    {
        $categories = \App\Models\Stock\StockExpenseCategory::all();
        $suppliers = \App\Models\Stock\StockSupplier::all();

        return view('stock.expenses.edit', [
            'expense' => $stockExpense->load(['category', 'supplier']),
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockExpense $stockExpense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:stock_expense_categories,id',
            'supplier_id' => 'nullable|exists:stock_suppliers,id',
            'amount' => 'required|numeric|min:0',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'payment_status' => 'required|in:paid,pending,overdue',
            'due_date' => 'nullable|date|after_or_equal:expense_date',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Calculate VAT amounts
        $vatRate = $validated['vat_rate'] ?? 0;
        $amount = $validated['amount'];
        $vatAmount = ($amount * $vatRate) / 100;
        $totalAmount = $amount + $vatAmount;

        $validated['vat_amount'] = $vatAmount;
        $validated['total_amount'] = $totalAmount;

        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($stockExpense->receipt_path) {
                Storage::disk('public')->delete($stockExpense->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('stock/expenses', 'public');
        }

        $oldPaymentStatus = $stockExpense->payment_status;
        $stockExpense->update($validated);

        // Handle account movements
        if ($validated['payment_status'] === 'paid' && $oldPaymentStatus !== 'paid') {
            // Create account movement when status changes to paid
            \App\Models\Stock\StockAccountMovement::create([
                'reference_type' => StockExpense::class,
                'reference_id' => $stockExpense->id,
                'type' => 'expense',
                'amount' => $totalAmount,
                'description' => $validated['title'],
                'movement_date' => $validated['expense_date'],
            ]);
        } elseif ($validated['payment_status'] !== 'paid' && $oldPaymentStatus === 'paid') {
            // Remove account movement if status changes from paid
            $stockExpense->accountMovements()->delete();
        } elseif ($oldPaymentStatus === 'paid' && $validated['payment_status'] === 'paid') {
            // Update existing account movement
            $movement = $stockExpense->accountMovements()->first();
            if ($movement) {
                $movement->update([
                    'amount' => $totalAmount,
                    'description' => $validated['title'],
                    'movement_date' => $validated['expense_date'],
                ]);
            }
        }

        return redirect()->route('stock.expenses.show', $stockExpense)->with('success', 'Gider başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockExpense $stockExpense)
    {
        // Delete receipt file if exists
        if ($stockExpense->receipt_path) {
            Storage::disk('public')->delete($stockExpense->receipt_path);
        }

        // Delete associated account movements
        $stockExpense->accountMovements()->delete();

        $stockExpense->delete();

        return redirect()->route('stock.expenses.index')->with('success', 'Gider başarıyla silindi.');
    }
}
