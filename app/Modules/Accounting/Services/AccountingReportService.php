<?php

namespace App\Modules\Accounting\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Enums\InvoiceStatus;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AccountingReportService
{
    public function getTotalRevenue(): float
    {
        return Invoice::where('status', InvoiceStatus::PAID)->sum('grand_total');
    }

    public function getMonthlyRevenue(Carbon $startDate = null, Carbon $endDate = null): float
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        return Invoice::where('status', InvoiceStatus::PAID)
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('grand_total');
    }

    public function getWeeklyRevenue(): float
    {
        return $this->getMonthlyRevenue(now()->startOfWeek(), now()->endOfWeek());
    }

    public function getDailyRevenue(Carbon $date = null): float
    {
        $date = $date ?? now();

        return Invoice::where('status', InvoiceStatus::PAID)
            ->whereDate('paid_at', $date->toDateString())
            ->sum('grand_total');
    }

    public function getRevenueByDateRange(Carbon $startDate, Carbon $endDate): Collection
    {
        return Invoice::where('status', InvoiceStatus::PAID)
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('DATE(paid_at) as date, SUM(grand_total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getOverdueInvoices(): Collection
    {
        return Invoice::where('status', InvoiceStatus::OVERDUE)
            ->orWhere(function ($query) {
                $query->where('due_date', '<', now()->startOfDay())
                      ->where('status', InvoiceStatus::POSTPONED);
            })
            ->with(['patient', 'items'])
            ->orderBy('due_date')
            ->get();
    }

    public function getRevenueByDentist(Carbon $startDate = null, Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        return Invoice::join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->join('patient_treatments', 'invoice_items.patient_treatment_id', '=', 'patient_treatments.id')
            ->join('users', 'patient_treatments.dentist_id', '=', 'users.id')
            ->where('invoices.status', InvoiceStatus::PAID)
            ->whereBetween('invoices.paid_at', [$startDate, $endDate])
            ->selectRaw('users.name as dentist_name, SUM(invoice_items.line_total) as revenue')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getPaymentMethodStats(Carbon $startDate = null, Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        return Payment::whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('method')
            ->orderByDesc('total')
            ->get();
    }

    public function getOutstandingBalances(): Collection
    {
        return Invoice::whereIn('status', [InvoiceStatus::ISSUED, InvoiceStatus::PARTIAL, InvoiceStatus::INSTALLMENT])
            ->with('patient')
            ->get()
            ->map(function ($invoice) {
                $paid = $invoice->payments()->sum('amount');
                return [
                    'invoice' => $invoice,
                    'outstanding' => max($invoice->patient_payable_amount - $paid, 0),
                ];
            })
            ->filter(function ($item) {
                return $item['outstanding'] > 0;
            })
            ->sortByDesc('outstanding');
    }

    public function getInvoiceStatusSummary(): array
    {
        $statuses = Invoice::selectRaw('status, COUNT(*) as count, SUM(grand_total) as total')
            ->groupBy('status')
            ->get()
            ->pluck(null, 'status');

        return [
            'paid' => [
                'count' => $statuses[InvoiceStatus::PAID->value]['count'] ?? 0,
                'total' => $statuses[InvoiceStatus::PAID->value]['total'] ?? 0,
            ],
            'pending' => [
                'count' => $statuses[InvoiceStatus::ISSUED->value]['count'] ?? 0,
                'total' => $statuses[InvoiceStatus::ISSUED->value]['total'] ?? 0,
            ],
            'overdue' => [
                'count' => $statuses[InvoiceStatus::OVERDUE->value]['count'] ?? 0,
                'total' => $statuses[InvoiceStatus::OVERDUE->value]['total'] ?? 0,
            ],
            'partial' => [
                'count' => $statuses[InvoiceStatus::PARTIAL->value]['count'] ?? 0,
                'total' => $statuses[InvoiceStatus::PARTIAL->value]['total'] ?? 0,
            ],
            'installment' => [
                'count' => $statuses[InvoiceStatus::INSTALLMENT->value]['count'] ?? 0,
                'total' => $statuses[InvoiceStatus::INSTALLMENT->value]['total'] ?? 0,
            ],
        ];
    }

    public function getPaidInvoicesToday(): int
    {
        return Invoice::where('status', InvoiceStatus::PAID)
            ->whereDate('paid_at', today())
            ->count();
    }

    public function getTopPayingPatients(int $limit = 10): Collection
    {
        return Invoice::join('patients', 'invoices.patient_id', '=', 'patients.id')
            ->where('invoices.status', InvoiceStatus::PAID)
            ->selectRaw('patients.first_name, patients.last_name, SUM(invoices.grand_total) as total_paid, COUNT(invoices.id) as invoice_count')
            ->groupBy('patients.id', 'patients.first_name', 'patients.last_name')
            ->orderByDesc('total_paid')
            ->limit($limit)
            ->get();
    }
}