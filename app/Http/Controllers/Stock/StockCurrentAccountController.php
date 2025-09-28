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

        $query = $supplier->purchaseInvoices()->with('items')->latest('invoice_date')->latest();

        if ($status = $request->string('status')->toString()) {
            $query->where('payment_status', $status);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('invoice_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('invoice_date', '<=', $to);
        }

        return view('stock.current.show', [
            'supplier' => $supplier,
            'invoices' => $query->paginate(20)->withQueryString(),
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
