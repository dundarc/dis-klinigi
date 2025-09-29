<?php

namespace App\Modules\Accounting\Listeners;

use App\Modules\Accounting\Events\InvoiceOverdue;
use Illuminate\Support\Facades\Log;

class LogOverdueInvoice
{
    public function handle(InvoiceOverdue $event): void
    {
        $invoice = $event->invoice;

        Log::warning('Invoice marked as overdue', [
            'invoice_id' => $invoice->id,
            'invoice_no' => $invoice->invoice_no,
            'patient_id' => $invoice->patient_id,
            'patient_name' => $invoice->patient?->first_name . ' ' . $invoice->patient?->last_name,
            'amount' => $invoice->grand_total,
            'due_date' => $invoice->due_date,
            'days_overdue' => $invoice->due_date ? now()->diffInDays($invoice->due_date) : null,
        ]);
    }
}