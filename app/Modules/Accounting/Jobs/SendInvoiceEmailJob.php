<?php

namespace App\Modules\Accounting\Jobs;

use App\Models\Invoice;
use App\Mail\InvoiceMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    protected Invoice $invoice;
    protected string $type;

    public function __construct(Invoice $invoice, string $type = 'created')
    {
        $this->invoice = $invoice;
        $this->type = $type;
    }

    public function handle(): void
    {
        try {
            if (!$this->invoice->patient || !$this->invoice->patient->email) {
                Log::warning('Cannot send invoice email: patient email not found', [
                    'invoice_id' => $this->invoice->id,
                    'patient_id' => $this->invoice->patient_id,
                ]);
                return;
            }

            Mail::to($this->invoice->patient->email)
                ->send(new InvoiceMail($this->invoice->load('patient', 'items'), $this->type));

            Log::info('Invoice email sent successfully', [
                'invoice_id' => $this->invoice->id,
                'type' => $this->type,
                'email' => $this->invoice->patient->email,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send invoice email', [
                'invoice_id' => $this->invoice->id,
                'type' => $this->type,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SendInvoiceEmailJob failed permanently', [
            'invoice_id' => $this->invoice->id,
            'type' => $this->type,
            'error' => $exception->getMessage(),
        ]);
    }
}