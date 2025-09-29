<?php

namespace App\Modules\Accounting\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Enums\InvoiceStatus;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function processPayment(Invoice $invoice, array $paymentData): Payment
    {
        return DB::transaction(function () use ($invoice, $paymentData) {
            $payment = $invoice->payments()->create($paymentData);

            $this->updateInvoicePaymentStatus($invoice);

            return $payment;
        });
    }

    public function processPartialPayment(Invoice $invoice, float $amount, string $method, string $date): Payment
    {
        $remainingAmount = max($invoice->patient_payable_amount - $invoice->payments()->sum('amount'), 0);

        if ($amount > $remainingAmount) {
            $amount = $remainingAmount;
        }

        return $this->processPayment($invoice, [
            'amount' => $amount,
            'method' => $method,
            'paid_at' => $date,
        ]);
    }

    public function processInstallmentPayment(Invoice $invoice, int $installmentSequence, float $amount, string $method, string $date): Payment
    {
        $paymentDetails = $invoice->payment_details ?? [];
        $installments = $paymentDetails['installments'] ?? [];

        // Mark installment as paid
        foreach ($installments as $index => $installment) {
            if ($installment['sequence'] == $installmentSequence) {
                $installments[$index]['status'] = 'paid';
                break;
            }
        }

        $paymentDetails['installments'] = $installments;
        $invoice->update(['payment_details' => $paymentDetails]);

        return $this->processPayment($invoice, [
            'amount' => $amount,
            'method' => $method,
            'paid_at' => $date,
        ]);
    }

    public function getPaymentHistory(Invoice $invoice)
    {
        return $invoice->payments()->orderBy('paid_at', 'desc')->get();
    }

    public function calculateOutstandingBalance(Invoice $invoice): float
    {
        $totalPaid = $invoice->payments()->sum('amount');
        return max($invoice->patient_payable_amount - $totalPaid, 0);
    }

    protected function updateInvoicePaymentStatus(Invoice $invoice): void
    {
        $outstandingBalance = $this->calculateOutstandingBalance($invoice);

        if ($outstandingBalance <= 0) {
            $invoice->update(['status' => InvoiceStatus::PAID]);
        } elseif ($invoice->payments()->exists()) {
            $invoice->update(['status' => InvoiceStatus::PARTIAL]);
        }
    }
}