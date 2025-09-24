<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockExpense;
use App\Models\Stock\StockItem;
use App\Models\Stock\StockMovement;
use App\Models\Stock\StockPurchaseInvoice;
use App\Models\Stock\StockUsage;
use Carbon\Carbon;

class StockDashboardController extends Controller
{
    public function index()
    {
        $this->authorize('accessStockManagement');

        $items = StockItem::with('category')->orderBy('name')->get();
        $criticalItems = $items->filter->isBelowMinimum();
        $negativeItems = $items->filter(fn ($item) => (float) $item->quantity < 0);

        $recentMovements = StockMovement::with(['stockItem'])->latest()->limit(10)->get();
        $pendingInvoices = StockPurchaseInvoice::pending()->with('supplier')->orderByDesc('invoice_date')->limit(5)->get();
        $overdueInvoices = StockPurchaseInvoice::overdue()->with('supplier')->orderByDesc('invoice_date')->limit(5)->get();
        $recentUsage = StockUsage::with(['recordedBy', 'items.stockItem'])->latest()->limit(5)->get();

        $currentMonth = Carbon::now()->startOfMonth();
        $monthlyExpenses = StockExpense::whereDate('expense_date', '>=', $currentMonth)->sum('total_amount');
        $monthlyPurchases = StockPurchaseInvoice::whereDate('invoice_date', '>=', $currentMonth)->sum('grand_total');

        $summary = [
            'total_items' => $items->count(),
            'active_items' => $items->where('is_active', true)->count(),
            'total_stock_quantity' => (float) $items->sum(fn ($item) => (float) $item->quantity),
            'critical_count' => $criticalItems->count(),
            'negative_count' => $negativeItems->count(),
            'monthly_expenses' => $monthlyExpenses,
            'monthly_purchases' => $monthlyPurchases,
        ];

        return view('stock.dashboard', [
            'summary' => $summary,
            'criticalItems' => $criticalItems,
            'negativeItems' => $negativeItems,
            'recentMovements' => $recentMovements,
            'pendingInvoices' => $pendingInvoices,
            'overdueInvoices' => $overdueInvoices,
            'recentUsage' => $recentUsage,
        ]);
    }
}
