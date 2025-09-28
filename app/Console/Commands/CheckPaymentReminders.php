<?php

namespace App\Console\Commands;

use App\Services\Stock\PaymentReminderService;
use Illuminate\Console\Command;

class CheckPaymentReminders extends Command
{
    protected $signature = 'payments:check-reminders 
                          {--days=3 : Days before due date to send reminders}
                          {--update-overdue : Update overdue payment statuses}';

    protected $description = 'Check for upcoming payment due dates and send reminders';

    public function __construct(private readonly PaymentReminderService $reminderService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $daysBefore = (int) $this->option('days');
        
        $this->info("Checking payment reminders for {$daysBefore} days before due date...");
        
        // Update overdue payments if requested
        if ($this->option('update-overdue')) {
            $this->info('Updating overdue payment statuses...');
            $updated = $this->reminderService->updateOverduePayments();
            $this->info("Updated {$updated} overdue payments.");
        }
        
        // Send payment reminders
        $remindersSent = $this->reminderService->sendPaymentReminders($daysBefore);
        $this->info("Sent {$remindersSent} payment reminders.");
        
        // Show dashboard statistics
        $stats = $this->reminderService->getDashboardStats();
        
        $this->table(['Metric', 'Value'], [
            ['Total Overdue Amount', number_format($stats['overdue']['total_overdue_amount'], 2) . ' ₺'],
            ['Overdue Invoices', $stats['overdue']['overdue_invoice_count']],
            ['Overdue Installments', $stats['overdue']['overdue_schedule_count']],
            ['Due This Week', number_format($stats['due_this_week'], 2) . ' ₺'],
            ['Due This Month', number_format($stats['due_this_month'], 2) . ' ₺'],
        ]);
        
        return Command::SUCCESS;
    }
}