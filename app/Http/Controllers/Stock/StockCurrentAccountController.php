<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockAccountMovement;
use App\Models\Stock\StockSupplier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockCurrentAccountController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('accessStockManagement');

        $suppliers = StockSupplier::with(['purchaseInvoices', 'accountMovements'])->orderBy('name')->get()
            ->map(function (StockSupplier $supplier) {
                return [
                    'supplier' => $supplier,
                    'total_debt' => $supplier->total_debt,
                    'total_paid' => $supplier->total_paid,
                    'remaining_debt' => $supplier->total_debt - $supplier->total_paid,
                    'overdue_amount' => $supplier->overdue_amount,
                    'overdue_invoices_count' => $supplier->overdue_invoices->count(),
                ];
            });

        return view('stock.current.index', compact('suppliers'));
    }

    public function show(StockSupplier $supplier, Request $request): View
    {
        $this->authorize('accessStockManagement');

        // Load invoices
        $invoiceQuery = $supplier->purchaseInvoices()->with('items')->latest('invoice_date')->latest();

        if ($status = $request->string('status')->toString()) {
            $invoiceQuery->where('payment_status', $status);
        }

        if ($from = $request->input('date_from')) {
            $invoiceQuery->whereDate('invoice_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $invoiceQuery->whereDate('invoice_date', '<=', $to);
        }

        $invoices = $invoiceQuery->paginate(20)->withQueryString();

        // Load expenses for service suppliers
        $expenses = collect();
        if ($supplier->type === 'service') {
            $expenseQuery = $supplier->expenses()->with('category')->latest('expense_date')->latest();

            if ($status = $request->string('status')->toString()) {
                $expenseQuery->where('payment_status', $status);
            }

            if ($from = $request->input('date_from')) {
                $expenseQuery->whereDate('expense_date', '>=', $from);
            }

            if ($to = $request->input('date_to')) {
                $expenseQuery->whereDate('expense_date', '<=', $to);
            }

            $expenses = $expenseQuery->paginate(20)->withQueryString();
        }

        return view('stock.current.show', [
            'supplier' => $supplier,
            'invoices' => $invoices,
            'expenses' => $expenses,
            'filters' => $request->only(['status', 'date_from', 'date_to']),
            'summary' => [
                'total_debt' => $supplier->total_debt,
                'total_paid' => $supplier->total_paid,
                'remaining_debt' => $supplier->total_debt - $supplier->total_paid,
                'overdue_amount' => $supplier->overdue_amount,
                'overdue_invoices_count' => $supplier->overdue_invoices->count(),
            ],
        ]);
    }
}
