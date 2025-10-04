<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockExpense;
use App\Models\Stock\StockItem;
use App\Models\Stock\StockMovement;
use App\Models\Stock\StockPurchaseInvoice;
use App\Models\Stock\StockSupplier;
use App\Models\Stock\StockUsage;
use App\Models\StockExpense as GeneralExpense;
use App\Models\ServiceExpense;
use App\Enums\PaymentStatus;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $recentGeneralExpenses = GeneralExpense::latest('expense_date')->limit(5)->get();
        $recentServiceExpenses = ServiceExpense::latest('invoice_date')->limit(5)->get();

        $currentMonth = Carbon::now()->startOfMonth();
        $monthlyStockExpenses = StockExpense::whereDate('expense_date', '>=', $currentMonth)->sum('total_amount');
        $monthlyPurchases = StockPurchaseInvoice::whereDate('invoice_date', '>=', $currentMonth)->where('is_cancelled', false)->sum('grand_total');
        $monthlyGeneralExpenses = 0; // GeneralExpense is the same as StockExpense, avoid double counting
        $monthlyServiceExpenses = ServiceExpense::whereDate('invoice_date', '>=', $currentMonth)->sum('amount');

        $pendingInvoicesCount = StockPurchaseInvoice::pending()->count();
        $overdueServiceExpenses = ServiceExpense::overdue()->count();

        $summary = [
            'total_items' => $items->count(),
            'active_items' => $items->where('is_active', true)->count(),
            'total_stock_quantity' => (float) $items->sum(fn ($item) => (float) $item->quantity),
            'critical_count' => $criticalItems->count(),
            'negative_count' => $negativeItems->count(),
            'monthly_expenses' => $monthlyStockExpenses + $monthlyGeneralExpenses + $monthlyServiceExpenses,
            'monthly_stock_expenses' => $monthlyStockExpenses,
            'monthly_purchases' => $monthlyPurchases,
            'monthly_general_expenses' => $monthlyGeneralExpenses,
            'monthly_service_expenses' => $monthlyServiceExpenses,
            'total_monthly_expenses' => $monthlyStockExpenses + $monthlyGeneralExpenses + $monthlyServiceExpenses,
            'pending_invoices' => $pendingInvoicesCount,
            'overdue_services' => $overdueServiceExpenses,
        ];

        // Prepare data for the new dashboard design
        $criticalStockItems = $criticalItems->take(5);
        $negativeStockItems = $negativeItems->take(5);
        $recentTransactions = $recentMovements->take(5);
        $recentUserLogs = $recentUsage->take(5); // Assuming this is user logs

        // Prepare data for the dashboard view
        $summary = [
            'total_items' => $items->count(),
            'active_items' => $items->where('is_active', true)->count(),
            'total_stock_quantity' => (float) $items->sum(fn ($item) => (float) $item->quantity),
            'critical_count' => $criticalItems->count(),
            'negative_count' => $negativeItems->count(),
            'monthly_expenses' => $monthlyStockExpenses + $monthlyGeneralExpenses + $monthlyServiceExpenses,
            'monthly_stock_expenses' => $monthlyStockExpenses,
            'monthly_purchases' => $monthlyPurchases,
            'monthly_general_expenses' => $monthlyGeneralExpenses,
            'monthly_service_expenses' => $monthlyServiceExpenses,
            'total_monthly_expenses' => $monthlyStockExpenses + $monthlyGeneralExpenses + $monthlyServiceExpenses,
            'pending_invoices' => $pendingInvoicesCount,
            'overdue_services' => $overdueServiceExpenses,
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

    public function search(Request $request): JsonResponse
    {
        $this->authorize('accessStockManagement');

        $query = $request->get('q', '');

        $results = [];

        // Stock Items
        $items = StockItem::where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->orWhere('barcode', 'like', "%{$query}%")
            ->with('category')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->sku ?? $item->barcode,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'category' => $item->category?->name,
                    'type' => 'item',
                    'url' => route('stock.items.show', $item)
                ];
            });
        $results = array_merge($results, $items->toArray());

        // Suppliers
        $suppliers = StockSupplier::where('name', 'like', "%{$query}%")
            ->orWhere('tax_number', 'like', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'code' => $supplier->tax_number,
                    'type' => 'supplier',
                    'url' => route('stock.current.show', $supplier)
                ];
            });
        $results = array_merge($results, $suppliers->toArray());

        // Services
        $services = ServiceExpense::where('service_provider', 'like', "%{$query}%")
            ->orWhere('service_type', 'like', "%{$query}%")
            ->orWhere('notes', 'like', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->service_provider . ' - ' . $service->service_type,
                    'supplier' => $service->service_provider,
                    'amount' => $service->amount,
                    'type' => 'service',
                    'url' => '#'
                ];
            });
        $results = array_merge($results, $services->toArray());

        // Invoices
        $invoices = StockPurchaseInvoice::where('invoice_number', 'like', "%{$query}%")
            ->with('supplier')
            ->limit(10)
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'name' => $invoice->invoice_number,
                    'supplier' => $invoice->supplier?->name,
                    'total' => $invoice->grand_total,
                    'type' => 'invoice',
                    'url' => route('stock.purchases.show', $invoice)
                ];
            });
        $results = array_merge($results, $invoices->toArray());

        // Expenses
        $expenses = StockExpense::where('title', 'like', "%{$query}%")
            ->orWhere('notes', 'like', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(function ($expense) {
                return [
                    'id' => $expense->id,
                    'name' => $expense->title,
                    'amount' => $expense->total_amount,
                    'type' => 'expense',
                    'url' => route('stock.expenses.show', $expense)
                ];
            });
        $results = array_merge($results, $expenses->toArray());

        return response()->json($results);
    }

    public function getPartialInvoices(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isAccountant()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $page = $request->get('page', 1);
        $perPage = 5;

        $invoices = StockPurchaseInvoice::partial()
            ->with('supplier')
            ->orderByDesc('invoice_date')
            ->paginate($perPage, ['*'], 'page', $page);

        $data = $invoices->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'supplier_name' => $invoice->supplier?->name,
                'invoice_date' => $invoice->invoice_date?->format('d.m.Y'),
                'grand_total' => number_format($invoice->grand_total, 2, ',', '.'),
                'url' => route('stock.purchases.show', $invoice)
            ];
        });

        return response()->json([
            'data' => $data,
            'current_page' => $invoices->currentPage(),
            'last_page' => $invoices->lastPage(),
            'per_page' => $invoices->perPage(),
            'total' => $invoices->total()
        ]);
    }

    public function getOverdueInvoices(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isAccountant()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $page = $request->get('page', 1);
        $perPage = 5;

        $invoices = StockPurchaseInvoice::overdue()
            ->with('supplier')
            ->orderByDesc('invoice_date')
            ->paginate($perPage, ['*'], 'page', $page);

        $data = $invoices->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'supplier_name' => $invoice->supplier?->name,
                'invoice_date' => $invoice->invoice_date?->format('d.m.Y'),
                'grand_total' => number_format($invoice->grand_total, 2, ',', '.'),
                'url' => route('stock.purchases.show', $invoice)
            ];
        });

        return response()->json([
            'data' => $data,
            'current_page' => $invoices->currentPage(),
            'last_page' => $invoices->lastPage(),
            'per_page' => $invoices->perPage(),
            'total' => $invoices->total()
        ]);
    }

    public function getCriticalStocks(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isAccountant()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $page = $request->get('page', 1);
        $perPage = 5;

        $items = StockItem::with('category')
            ->whereRaw('quantity <= minimum_quantity')
            ->orderBy('quantity')
            ->paginate($perPage, ['*'], 'page', $page);

        $data = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'category_name' => $item->category?->name,
                'quantity' => number_format($item->quantity, 2),
                'unit' => $item->unit,
                'url' => route('stock.items.show', $item)
            ];
        });

        return response()->json([
            'data' => $data,
            'current_page' => $items->currentPage(),
            'last_page' => $items->lastPage(),
            'per_page' => $items->perPage(),
            'total' => $items->total()
        ]);
    }

    public function getNegativeStocks(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isAccountant()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $page = $request->get('page', 1);
        $perPage = 5;

        $items = StockItem::with('category')
            ->where('quantity', '<', 0)
            ->orderBy('quantity')
            ->paginate($perPage, ['*'], 'page', $page);

        $data = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'category_name' => $item->category?->name,
                'quantity' => number_format($item->quantity, 2),
                'unit' => $item->unit,
                'url' => route('stock.items.show', $item)
            ];
        });

        return response()->json([
            'data' => $data,
            'current_page' => $items->currentPage(),
            'last_page' => $items->lastPage(),
            'per_page' => $items->perPage(),
            'total' => $items->total()
        ]);
    }
}
