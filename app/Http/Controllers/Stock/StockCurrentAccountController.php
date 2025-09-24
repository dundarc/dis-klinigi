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

        $suppliers = StockSupplier::with(['accountMovements'])->orderBy('name')->get()
            ->map(function (StockSupplier $supplier) {
                $debit = $supplier->accountMovements->where('direction', 'debit')->sum('amount');
                $credit = $supplier->accountMovements->where('direction', 'credit')->sum('amount');

                return [
                    'supplier' => $supplier,
                    'balance' => $debit - $credit,
                    'debit' => $debit,
                    'credit' => $credit,
                ];
            });

        return view('stock.current.index', compact('suppliers'));
    }

    public function show(StockSupplier $supplier, Request $request): View
    {
        $this->authorize('accessStockManagement');

        $query = $supplier->accountMovements()->latest('movement_date')->latest();

        if ($direction = $request->string('direction')->toString()) {
            $query->where('direction', $direction);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('movement_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('movement_date', '<=', $to);
        }

        return view('stock.current.show', [
            'supplier' => $supplier,
            'movements' => $query->paginate(20)->withQueryString(),
            'filters' => $request->only(['direction', 'date_from', 'date_to']),
        ]);
    }
}
