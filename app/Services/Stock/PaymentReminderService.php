<?php

namespace App\Services\Stock;

use App\Models\Stock\StockPurchaseInvoice;
use App\Models\Stock\StockPaymentSchedule;
use App\Enums\PaymentStatus;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PaymentReminderService
{
    /**
     * Check for overdue payments and update statuses
     */
    public function updateOverduePayments(): int
    {
        $updated = 0;
        
        // Update overdue regular invoices
        $overdueInvoices = StockPurchaseInvoice::where('due_date', '<', now())
            ->whereIn('payment_status', [PaymentStatus::PENDING, PaymentStatus::PARTIAL])
            ->where('is_installment', false)
            ->get();
            
        foreach ($overdueInvoices as $invoice) {
            $invoice->update(['payment_status' => PaymentStatus::OVERDUE]);
            $updated++;
        }
        
        // Update overdue installment schedules
        $overdueSchedules = StockPaymentSchedule::where('due_date', '<', now())
            ->where('status', PaymentStatus::PENDING)
            ->get();
            
        foreach ($overdueSchedules as $schedule) {
            $schedule->update(['status' => PaymentStatus::OVERDUE]);
            $schedule->invoice->updatePaymentStatus();
            $updated++;
        }
        
        return $updated;
    }

    /**
     * Get upcoming payment reminders
     */
    public function getUpcomingPayments(int $daysBefore = 7): Collection
    {
        $targetDate = now()->addDays($daysBefore);
        
        $upcomingInvoices = StockPurchaseInvoice::where('due_date', '<=', $targetDate)
            ->where('due_date', '>=', now())
            ->whereIn('payment_status', [PaymentStatus::PENDING, PaymentStatus::PARTIAL])
            ->where('is_installment', false)
            ->with('supplier')
            ->get();
            
        $upcomingSchedules = StockPaymentSchedule::where('due_date', '<=', $targetDate)
            ->where('due_date', '>=', now())
            ->whereIn('status', [PaymentStatus::PENDING, PaymentStatus::PARTIAL])
            ->with(['invoice.supplier'])
            ->get();
            
        return collect([
            'invoices' => $upcomingInvoices,
            'schedules' => $upcomingSchedules,
            'total_amount' => $upcomingInvoices->sum('remaining_amount') + 
                            $upcomingSchedules->sum('remaining_amount')
        ]);
    }

    /**
     * Send payment reminders
     */
    public function sendPaymentReminders(int $daysBefore = 3): int
    {
        $remindersSent = 0;
        $upcoming = $this->getUpcomingPayments($daysBefore);
        
        // Send reminders for regular invoices
        foreach ($upcoming['invoices'] as $invoice) {
            $this->createPaymentReminder(
                'invoice_payment_due',
                "Fatura ödemesi yaklaşıyor: #{$invoice->invoice_number}",
                "Tedarikçi: {$invoice->supplier->name}, Tutar: " . number_format($invoice->remaining_amount, 2) . " ₺, Vade: " . $invoice->due_date->format('d.m.Y'),
                [
                    'invoice_id' => $invoice->id,
                    'amount' => $invoice->remaining_amount,
                    'due_date' => $invoice->due_date,
                    'supplier' => $invoice->supplier->name
                ]
            );
            $remindersSent++;
        }
        
        // Send reminders for installment schedules
        foreach ($upcoming['schedules'] as $schedule) {
            $this->createPaymentReminder(
                'installment_payment_due',
                "Taksit ödemesi yaklaşıyor: #{$schedule->invoice->invoice_number} - Taksit {$schedule->installment_number}",
                "Tedarikçi: {$schedule->invoice->supplier->name}, Tutar: " . number_format($schedule->remaining_amount, 2) . " ₺, Vade: " . $schedule->due_date->format('d.m.Y'),
                [
                    'schedule_id' => $schedule->id,
                    'invoice_id' => $schedule->invoice->id,
                    'installment_number' => $schedule->installment_number,
                    'amount' => $schedule->remaining_amount,
                    'due_date' => $schedule->due_date,
                    'supplier' => $schedule->invoice->supplier->name
                ]
            );
            $remindersSent++;
        }
        
        return $remindersSent;
    }

    /**
     * Get overdue payment statistics
     */
    public function getOverdueStatistics(): array
    {
        $overdueInvoices = StockPurchaseInvoice::where('payment_status', PaymentStatus::OVERDUE)
            ->with('supplier')
            ->get();
            
        $overdueSchedules = StockPaymentSchedule::where('status', PaymentStatus::OVERDUE)
            ->with(['invoice.supplier'])
            ->get();
            
        return [
            'total_overdue_amount' => $overdueInvoices->sum('remaining_amount') + 
                                    $overdueSchedules->sum('remaining_amount'),
            'overdue_invoice_count' => $overdueInvoices->count(),
            'overdue_schedule_count' => $overdueSchedules->count(),
            'oldest_overdue_date' => $this->getOldestOverdueDate($overdueInvoices, $overdueSchedules),
            'overdue_by_supplier' => $this->groupOverdueBySupplier($overdueInvoices, $overdueSchedules)
        ];
    }

    /**
     * Create dashboard statistics for payments
     */
    public function getDashboardStats(): array
    {
        $today = now();
        $nextWeek = $today->copy()->addWeek();
        $nextMonth = $today->copy()->addMonth();
        
        return [
            'overdue' => $this->getOverdueStatistics(),
            'due_this_week' => $this->getAmountDueByPeriod($today, $nextWeek),
            'due_this_month' => $this->getAmountDueByPeriod($today, $nextMonth),
            'upcoming_payments' => $this->getUpcomingPayments(30)
        ];
    }

    /**
     * Create a payment reminder notification
     */
    protected function createPaymentReminder(string $type, string $title, string $message, array $data): void
    {
        Notification::create([
            'user_id' => null, // System notification
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    /**
     * Get oldest overdue date
     */
    protected function getOldestOverdueDate(Collection $invoices, Collection $schedules): ?Carbon
    {
        $oldestInvoice = $invoices->min('due_date');
        $oldestSchedule = $schedules->min('due_date');
        
        if (!$oldestInvoice && !$oldestSchedule) {
            return null;
        }
        
        if (!$oldestInvoice) {
            return Carbon::parse($oldestSchedule);
        }
        
        if (!$oldestSchedule) {
            return Carbon::parse($oldestInvoice);
        }
        
        return Carbon::parse($oldestInvoice)->lt(Carbon::parse($oldestSchedule)) 
            ? Carbon::parse($oldestInvoice) 
            : Carbon::parse($oldestSchedule);
    }

    /**
     * Group overdue amounts by supplier
     */
    protected function groupOverdueBySupplier(Collection $invoices, Collection $schedules): array
    {
        $bySupplier = [];
        
        foreach ($invoices as $invoice) {
            $supplierName = $invoice->supplier->name ?? 'Unknown';
            if (!isset($bySupplier[$supplierName])) {
                $bySupplier[$supplierName] = 0;
            }
            $bySupplier[$supplierName] += $invoice->remaining_amount;
        }
        
        foreach ($schedules as $schedule) {
            $supplierName = $schedule->invoice->supplier->name ?? 'Unknown';
            if (!isset($bySupplier[$supplierName])) {
                $bySupplier[$supplierName] = 0;
            }
            $bySupplier[$supplierName] += $schedule->remaining_amount;
        }
        
        arsort($bySupplier);
        
        return $bySupplier;
    }

    /**
     * Get amount due by period
     */
    protected function getAmountDueByPeriod(Carbon $start, Carbon $end): float
    {
        $invoiceAmount = StockPurchaseInvoice::whereBetween('due_date', [$start, $end])
            ->whereIn('payment_status', [PaymentStatus::PENDING, PaymentStatus::PARTIAL])
            ->where('is_installment', false)
            ->sum('grand_total');
            
        $scheduleAmount = StockPaymentSchedule::whereBetween('due_date', [$start, $end])
            ->whereIn('status', [PaymentStatus::PENDING, PaymentStatus::PARTIAL])
            ->sum('amount');
            
        return (float)$invoiceAmount + (float)$scheduleAmount;
    }
}