<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalCollected = Payment::sum('amount');
        $collectedThisMonth = Payment::whereBetween('paid_at', [$startOfMonth, $endOfMonth])->sum('amount');
        $invoiceCountThisMonth = Invoice::whereBetween('issue_date', [$startOfMonth, $endOfMonth])->count();

        $outstandingInvoices = Invoice::whereIn('status', [
            InvoiceStatus::UNPAID,
            InvoiceStatus::PARTIAL,
            InvoiceStatus::POSTPONED,
        ])->withSum('payments', 'amount')->get();

        $outstandingReceivables = $outstandingInvoices->sum(function (Invoice $invoice) {
            $paid = $invoice->payments_sum_amount ?? 0;
            return max($invoice->patient_payable_amount - $paid, 0);
        });

        $recentInvoices = Invoice::with(['patient'])
            ->withSum('payments', 'amount')
            ->latest('issue_date')
            ->take(8)
            ->get();

        $overdueInvoices = Invoice::whereIn('status', [
                InvoiceStatus::UNPAID,
                InvoiceStatus::PARTIAL,
                InvoiceStatus::POSTPONED,
            ])
            ->where('issue_date', '<', Carbon::now()->subDays(30))
            ->with(['patient'])
            ->withSum('payments', 'amount')
            ->orderBy('issue_date')
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['invoice.patient'])
            ->latest('paid_at')
            ->take(5)
            ->get();

        $paymentMethods = Payment::select('method', DB::raw('SUM(amount) as total'))
            ->groupBy('method')
            ->orderByDesc('total')
            ->get();

        $monthlyRevenue = Invoice::where('status', InvoiceStatus::PAID)
            ->where('issue_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(issue_date, '%Y-%m') as month, SUM(grand_total) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyRevenueChart = [
            'labels' => $monthlyRevenue->map(fn ($row) => Carbon::createFromFormat('Y-m', $row->month)
                ->locale(app()->getLocale())
                ->translatedFormat('M Y'))
                ->values(),
            'data' => $monthlyRevenue->pluck('total')->map(fn ($value) => (float) $value)->values(),
        ];

        $paymentMethodChart = [
            'labels' => $paymentMethods->pluck('method')->values(),
            'data' => $paymentMethods->pluck('total')->map(fn ($value) => (float) $value)->values(),
        ];

        $insuranceSummary = [
            'coverage' => Invoice::whereBetween('issue_date', [$startOfMonth, $endOfMonth])->sum('insurance_coverage_amount'),
            'patientPortion' => Invoice::whereBetween('issue_date', [$startOfMonth, $endOfMonth])->sum('patient_payable_amount'),
            'insuredInvoices' => Invoice::whereBetween('issue_date', [$startOfMonth, $endOfMonth])
                ->where('insurance_coverage_amount', '>', 0)
                ->count(),
        ];

        return view('accounting.index', [
            'metrics' => [
                'totalCollected' => $totalCollected,
                'collectedThisMonth' => $collectedThisMonth,
                'outstandingReceivables' => $outstandingReceivables,
                'invoiceCountThisMonth' => $invoiceCountThisMonth,
            ],
            'recentInvoices' => $recentInvoices,
            'overdueInvoices' => $overdueInvoices,
            'recentPayments' => $recentPayments,
            'monthlyRevenueChart' => $monthlyRevenueChart,
            'paymentMethodChart' => $paymentMethodChart,
            'insuranceSummary' => $insuranceSummary,
        ]);
    }
}