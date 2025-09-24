<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockAccountMovement;
use App\Models\Stock\StockExpense;
use App\Models\Stock\StockItem;
use App\Models\Stock\StockSupplier;
use App\Models\Stock\StockUsageItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockReportController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewStockReports');

        $start = Carbon::parse($request->input('start_date', now()->startOfMonth()->toDateString()))->startOfDay();
        $end = Carbon::parse($request->input('end_date', now()->endOfMonth()->toDateString()))->endOfDay();

        $expenseTotals = StockExpense::whereBetween('expense_date', [$start, $end]);
        $totalExpense = (clone $expenseTotals)->sum('total_amount');

        $categoryBreakdown = StockExpense::select('category_id', DB::raw('SUM(total_amount) as total'))
            ->whereBetween('expense_date', [$start, $end])
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(function ($row) {
                return [
                    'category' => $row->category?->name ?? 'Kategori Yok',
                    'total' => $row->total,
                ];
            });

        $usageSummary = StockUsageItem::select('stock_item_id', DB::raw('SUM(quantity) as total'))
            ->whereHas('usage', function ($query) use ($start, $end) {
                $query->whereBetween('used_at', [$start, $end]);
            })
            ->groupBy('stock_item_id')
            ->with('stockItem')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'item' => $row->stockItem?->name ?? 'Bilinmeyen',
                    'quantity' => $row->total,
                    'unit' => $row->stockItem?->unit ?? '',
                ];
            });

        $criticalItems = StockItem::with('category')->get()->filter->isBelowMinimum();
        $negativeStock = StockItem::with('category')->where('quantity', '<', 0)->get();

        $supplierBalances = StockSupplier::with('accountMovements')->get()->map(function (StockSupplier $supplier) {
            $debit = $supplier->accountMovements->where('direction', 'debit')->sum('amount');
            $credit = $supplier->accountMovements->where('direction', 'credit')->sum('amount');

            return [
                'supplier' => $supplier,
                'balance' => $debit - $credit,
            ];
        })->filter(fn ($row) => abs($row['balance']) > 0.01);

        return view('reports.stock.index', [
            'period' => [
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
            ],
            'totalExpense' => $totalExpense,
            'categoryBreakdown' => $categoryBreakdown,
            'usageSummary' => $usageSummary,
            'criticalItems' => $criticalItems,
            'negativeStock' => $negativeStock,
            'supplierBalances' => $supplierBalances,
        ]);
    }
}
