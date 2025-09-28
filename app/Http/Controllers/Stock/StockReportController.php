<?php

namespace App\Http\Controllers\Stock;

use App\Exports\StockExpensesExport;
use App\Exports\StockItemsExport;
use App\Http\Controllers\Controller;
use App\Models\Stock\StockAccountMovement;
use App\Models\Stock\StockExpense;
use App\Models\Stock\StockItem;
use App\Models\Stock\StockPurchase;
use App\Models\Stock\StockSupplier;
use App\Models\Stock\StockUsageItem;
use App\Models\ServiceExpense;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

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

    public function monthlyExpenseReport(Request $request): View
    {
        $this->authorize('viewStockReports');

        // Date range filtering
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        // Ensure start date is before end date
        if ($startDate->gt($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $start = $startDate->startOfDay();
        $end = $endDate->endOfDay();

        // Get expenses (stock + service expenses)
        $stockExpenses = StockExpense::with('category', 'supplier')
            ->whereBetween('expense_date', [$start, $end])
            ->get();

        $serviceExpenses = ServiceExpense::with('category')
            ->whereBetween('invoice_date', [$start, $end])
            ->get();

        $allExpenses = $stockExpenses->concat($serviceExpenses);

        // Calculate totals
        $stockTotal = $stockExpenses->sum('total_amount');
        $serviceTotal = $serviceExpenses->sum('amount');
        $totalAmount = $stockTotal + $serviceTotal;

        // Category breakdown for stock expenses
        $stockCategoryBreakdown = $stockExpenses->groupBy('category.name')->map(function ($group) {
            return [
                'category' => $group->first()->category?->name ?? 'Kategori Yok',
                'total' => $group->sum('total_amount'),
                'count' => $group->count(),
                'type' => 'stock'
            ];
        });

        // Category breakdown for service expenses
        $serviceCategoryBreakdown = $serviceExpenses->groupBy('service_type')->map(function ($group) {
            return [
                'category' => $group->first()->service_type ?? 'Hizmet',
                'total' => $group->sum('amount'),
                'count' => $group->count(),
                'type' => 'service'
            ];
        });

        $categoryBreakdown = $stockCategoryBreakdown->concat($serviceCategoryBreakdown);

        // Monthly chart data (last 12 months)
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();

            $monthStockExpenses = StockExpense::whereBetween('expense_date', [$monthStart, $monthEnd])->sum('total_amount');
            $monthServiceExpenses = ServiceExpense::whereBetween('invoice_date', [$monthStart, $monthEnd])->sum('amount');

            $chartData[] = [
                'month' => $monthStart->format('M Y'),
                'stock_expenses' => $monthStockExpenses,
                'service_expenses' => $monthServiceExpenses,
                'total' => $monthStockExpenses + $monthServiceExpenses
            ];
        }

        return view('reports.stock.monthly-expenses', [
            'stockExpenses' => $stockExpenses,
            'serviceExpenses' => $serviceExpenses,
            'allExpenses' => $allExpenses->sortByDesc('created_at'),
            'totalAmount' => $totalAmount,
            'stockTotal' => $stockTotal,
            'serviceTotal' => $serviceTotal,
            'categoryBreakdown' => $categoryBreakdown,
            'chartData' => $chartData,
            'period' => [
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
                'start_formatted' => $start->format('d.m.Y'),
                'end_formatted' => $end->format('d.m.Y'),
            ],
            'filters' => $request->only(['start_date', 'end_date']),
        ]);
    }

    public function serviceExpenseReport(Request $request): View
    {
        $this->authorize('viewStockReports');

        $query = ServiceExpense::query();

        if ($type = $request->input('service_type')) {
            $query->where('service_type', $type);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('invoice_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('invoice_date', '<=', $to);
        }

        $expenses = $query->orderBy('invoice_date', 'desc')->paginate(20)->withQueryString();

        $serviceTypes = ServiceExpense::distinct('service_type')->pluck('service_type');

        $summary = [
            'total_amount' => $query->sum('amount'),
            'paid_amount' => $query->paid()->sum('amount'),
            'pending_amount' => $query->pending()->sum('amount'),
            'overdue_amount' => $query->overdue()->sum('amount'),
        ];

        return view('reports.stock.service-expenses', [
            'expenses' => $expenses,
            'serviceTypes' => $serviceTypes,
            'summary' => $summary,
            'filters' => $request->only(['service_type', 'status', 'date_from', 'date_to']),
        ]);
    }

    public function currentAccountReport(Request $request)
    {
        $this->authorize('viewStockReports');

        $suppliers = StockSupplier::with(['purchaseInvoices'])->orderBy('name')->get()
            ->map(function (StockSupplier $supplier) {
                $invoices = $supplier->purchaseInvoices;

                return [
                    'supplier' => $supplier,
                    'total_debt' => $supplier->total_debt,
                    'total_paid' => $supplier->total_paid,
                    'remaining_debt' => $supplier->total_debt - $supplier->total_paid,
                    'overdue_amount' => $supplier->overdue_amount,
                    'invoice_count' => $invoices->count(),
                    'paid_invoice_count' => $invoices->where('payment_status', 'paid')->count(),
                    'pending_invoice_count' => $invoices->where('payment_status', 'pending')->count(),
                    'partial_invoice_count' => $invoices->where('payment_status', 'partial')->count(),
                    'overdue_invoice_count' => $supplier->overdue_invoices->count(),
                ];
            });

        // PDF export if requested
        if ($request->input('export') === 'pdf') {
            // Implement PDF export
            return response()->stream(function () use ($suppliers) {
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('reports.stock.current-account-pdf', compact('suppliers'));
                echo $pdf->output();
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="cari_hesap_raporu_' . now()->format('Ymd_His') . '.pdf"',
            ]);
        }

        return view('reports.stock.current-account', compact('suppliers'));
    }

    public function supplierReport(Request $request)
    {
        $this->authorize('viewStockReports');

        $supplierId = $request->input('supplier_id');

        if (!$supplierId) {
            $suppliers = StockSupplier::orderBy('name')->get();
            return view('reports.stock.supplier-select', compact('suppliers'));
        }

        $supplier = StockSupplier::with(['purchaseInvoices.payments', 'expenses'])->findOrFail($supplierId);

        // Calculate totals
        $totalDebt = $supplier->purchaseInvoices->sum('grand_total');
        $totalPaid = $supplier->purchaseInvoices->sum(function ($invoice) {
            return $invoice->payments->sum('amount');
        });
        $remainingDebt = $totalDebt - $totalPaid;

        // Overdue invoices
        $overdueInvoices = $supplier->purchaseInvoices->filter(function ($invoice) {
            return $invoice->due_date && $invoice->due_date->isPast() && $invoice->remaining_amount > 0;
        });

        // Recent invoices
        $recentInvoices = $supplier->purchaseInvoices()->with('payments')->latest()->take(10)->get();

        // Monthly spending trend (last 12 months)
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();

            $monthTotal = $supplier->purchaseInvoices()
                ->whereBetween('invoice_date', [$monthStart, $monthEnd])
                ->sum('grand_total');

            $monthlyData[] = [
                'month' => $monthStart->format('M Y'),
                'amount' => $monthTotal
            ];
        }

        return view('reports.stock.supplier-report', [
            'supplier' => $supplier,
            'totalDebt' => $totalDebt,
            'totalPaid' => $totalPaid,
            'remainingDebt' => $remainingDebt,
            'overdueInvoices' => $overdueInvoices,
            'recentInvoices' => $recentInvoices,
            'monthlyData' => $monthlyData,
        ]);
    }

    public function criticalStockReport(Request $request)
    {
        $this->authorize('viewStockReports');

        $query = StockItem::with('category')->where('is_active', true);

        // Filter by critical level
        if ($request->input('filter') === 'critical') {
            $query->where('minimum_quantity', '>', 0)
                  ->whereColumn('quantity', '<', 'minimum_quantity');
        } elseif ($request->input('filter') === 'low') {
            $query->where('minimum_quantity', '>', 0)
                  ->whereRaw('quantity <= minimum_quantity * 1.5')
                  ->whereRaw('quantity >= minimum_quantity');
        } elseif ($request->input('filter') === 'negative') {
            $query->where('quantity', '<', 0);
        } else {
            // All items below minimum or negative
            $query->where(function ($q) {
                $q->where('quantity', '<', 0)
                  ->orWhere(function ($subQ) {
                      $subQ->where('minimum_quantity', '>', 0)
                           ->whereColumn('quantity', '<', 'minimum_quantity');
                  });
            });
        }

        $criticalItems = $query->orderBy('quantity')->get()->map(function ($item) {
            $shortage = max(0, $item->minimum_quantity - $item->quantity);
            $suggestedOrder = $shortage > 0 ? $shortage + ($item->minimum_quantity * 0.2) : 0; // 20% buffer

            return [
                'item' => $item,
                'current_stock' => $item->quantity,
                'minimum_stock' => $item->minimum_quantity,
                'shortage' => $shortage,
                'suggested_order' => ceil($suggestedOrder),
                'status' => $item->quantity < 0 ? 'negative' : 'critical'
            ];
        });

        return view('reports.stock.critical-stock', [
            'criticalItems' => $criticalItems,
            'filter' => $request->input('filter', 'all'),
            'stats' => [
                'total_items' => $criticalItems->count(),
                'negative_stock' => $criticalItems->where('status', 'negative')->count(),
                'critical_stock' => $criticalItems->where('status', 'critical')->count(),
                'total_shortage_value' => $criticalItems->sum('shortage'),
            ]
        ]);
    }

    public function overdueInvoicesReport(Request $request)
    {
        $this->authorize('viewStockReports');

        $query = StockPurchase::with(['supplier', 'payments'])
            ->where('due_date', '<', now())
            ->whereRaw('total_amount > COALESCE((SELECT SUM(amount) FROM stock_purchase_payments WHERE stock_purchase_id = stock_purchases.id), 0)');

        // Filter by supplier
        if ($supplierId = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        // Filter by overdue days
        if ($days = $request->input('overdue_days')) {
            $query->where('due_date', '<', now()->subDays($days));
        }

        $overdueInvoices = $query->orderBy('due_date')->get()->map(function ($invoice) {
            $paidAmount = $invoice->payments->sum('amount');
            $remainingAmount = $invoice->total_amount - $paidAmount;
            $overdueDays = now()->diffInDays($invoice->due_date);

            return [
                'invoice' => $invoice,
                'supplier_name' => $invoice->supplier?->name ?? 'Bilinmiyor',
                'invoice_date' => $invoice->invoice_date,
                'due_date' => $invoice->due_date,
                'total_amount' => $invoice->total_amount,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'overdue_days' => $overdueDays,
            ];
        });

        $suppliers = StockSupplier::whereHas('purchaseInvoices', function ($q) {
            $q->where('due_date', '<', now())
              ->whereRaw('total_amount > COALESCE((SELECT SUM(amount) FROM stock_purchase_payments WHERE stock_purchase_id = stock_purchases.id), 0)');
        })->orderBy('name')->get();

        return view('reports.stock.overdue-invoices', [
            'overdueInvoices' => $overdueInvoices,
            'suppliers' => $suppliers,
            'filters' => $request->only(['supplier_id', 'overdue_days']),
            'stats' => [
                'total_invoices' => $overdueInvoices->count(),
                'total_amount' => $overdueInvoices->sum('remaining_amount'),
                'suppliers_count' => $overdueInvoices->unique('supplier_name')->count(),
            ]
        ]);
    }

    // Export methods
    public function exportMonthlyExpenses(Request $request)
    {
        $this->authorize('viewStockReports');

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        $filename = 'aylik_gider_raporu_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.xlsx';

        return Excel::download(new StockExpensesExport($request->input('q')), $filename);
    }

    public function exportMonthlyExpensesPdf(Request $request)
    {
        $this->authorize('viewStockReports');

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        $stockExpenses = StockExpense::with('category', 'supplier')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->get();

        $serviceExpenses = ServiceExpense::with('category')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->get();

        $pdf = Pdf::loadView('reports.stock.monthly-expenses-pdf', [
            'stockExpenses' => $stockExpenses,
            'serviceExpenses' => $serviceExpenses,
            'period' => [
                'start' => $startDate->format('d.m.Y'),
                'end' => $endDate->format('d.m.Y'),
            ],
            'generated_at' => now()->format('d.m.Y H:i'),
        ]);

        $filename = 'aylik_gider_raporu_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function printMonthlyExpenses(Request $request)
    {
        $this->authorize('viewStockReports');

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        $stockExpenses = StockExpense::with('category', 'supplier')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->get();

        $serviceExpenses = ServiceExpense::with('category')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->get();

        return view('reports.stock.monthly-expenses-print', [
            'stockExpenses' => $stockExpenses,
            'serviceExpenses' => $serviceExpenses,
            'period' => [
                'start' => $startDate->format('d.m.Y'),
                'end' => $endDate->format('d.m.Y'),
            ],
            'generated_at' => now()->format('d.m.Y H:i'),
        ]);
    }
}
