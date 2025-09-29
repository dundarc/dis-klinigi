<?php

namespace App\Modules\Accounting\Listeners;

use App\Modules\Accounting\Events\InvoicePaid;
use App\Modules\Accounting\Events\InvoiceOverdue;
use App\Modules\Accounting\Events\InvoiceDeleted;
use App\Modules\Accounting\Services\AccountingReportService;
use Illuminate\Support\Facades\Cache;

class UpdateAccountingReports
{
    protected AccountingReportService $reportService;

    public function __construct(AccountingReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function handle(InvoicePaid|InvoiceOverdue|InvoiceDeleted $event): void
    {
        // Clear cached reports when invoice status changes
        Cache::tags(['accounting-reports'])->flush();

        // Update real-time statistics
        $this->updateDashboardStats();
    }

    protected function updateDashboardStats(): void
    {
        $stats = [
            'total_revenue' => $this->reportService->getTotalRevenue(),
            'monthly_revenue' => $this->reportService->getMonthlyRevenue(),
            'overdue_count' => $this->reportService->getOverdueInvoices()->count(),
            'paid_today' => $this->reportService->getPaidInvoicesToday(),
        ];

        Cache::put('accounting-dashboard-stats', $stats, now()->addMinutes(5));
    }
}