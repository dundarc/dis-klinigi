<?php

namespace App\Modules\Accounting\Listeners;

use App\Modules\Accounting\Events\InvoicePaid;
use App\Modules\Accounting\Jobs\SendInvoiceEmailJob;
use Illuminate\Support\Facades\Log;

class SendInvoicePaidNotification
{
    public function handle(InvoicePaid $event): void
    {
        $invoice = $event->invoice;

        // Log the payment
        Log::info('Invoice marked as paid', [
            'invoice_id' => $invoice->id,
            'invoice_no' => $invoice->invoice_no,
            'patient_id' => $invoice->patient_id,
            'amount' => $invoice->grand_total,
        ]);

        // Send email notification if patient has email
        if ($invoice->patient && $invoice->patient->email) {
            SendInvoiceEmailJob::dispatch($invoice, 'paid');
        }
    }
}