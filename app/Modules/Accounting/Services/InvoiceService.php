<?php

namespace App\Modules\Accounting\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Enums\InvoiceStatus;
use App\Modules\Accounting\Repositories\InvoiceRepository;
use App\Modules\Accounting\Events\InvoicePaid;
use App\Modules\Accounting\Events\InvoiceOverdue;
use App\Modules\Accounting\Events\InvoiceDeleted;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceService
{
    protected InvoiceRepository $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function createInvoice(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $invoiceData = $this->calculateInvoiceTotals($data);
            $invoice = $this->invoiceRepository->create($invoiceData);

            foreach ($data['items'] as $item) {
                $invoice->items()->create($item + ['line_total' => $item['qty'] * $item['unit_price']]);
            }

            return $invoice->load('items');
        });
    }

    public function updateInvoiceStatus(Invoice $invoice, InvoiceStatus $status, array $additionalData = []): Invoice
    {
        $updateData = ['status' => $status];

        if ($status === InvoiceStatus::PAID) {
            $updateData['paid_at'] = $additionalData['paid_at'] ?? now();
            $updateData['payment_method'] = $additionalData['payment_method'] ?? null;
            event(new InvoicePaid($invoice));
        }

        if ($status === InvoiceStatus::OVERDUE) {
            event(new InvoiceOverdue($invoice));
        }

        return $this->invoiceRepository->update($invoice, $updateData);
    }

    public function deleteInvoice(Invoice $invoice): bool
    {
        event(new InvoiceDeleted($invoice));
        return $this->invoiceRepository->delete($invoice);
    }

    public function addPayment(Invoice $invoice, array $paymentData): Payment
    {
        $payment = $invoice->payments()->create($paymentData);

        // Check if invoice is fully paid
        $totalPaid = $invoice->payments()->sum('amount');
        if ($totalPaid >= $invoice->patient_payable_amount) {
            $this->updateInvoiceStatus($invoice, InvoiceStatus::PAID);
        }

        return $payment;
    }

    public function generateInstallmentPlan(Invoice $invoice, int $installmentCount, string $firstDueDate): array
    {
        if ($installmentCount <= 0) {
            throw new \App\Exceptions\InstallmentPlanException('Geçerli bir taksit sayısı giriniz.');
        }

        $startDate = Carbon::parse($firstDueDate);
        $total = (float) $invoice->patient_payable_amount;
        $baseAmount = round($total / $installmentCount, 2);

        $plan = [];
        $accumulated = 0.0;

        for ($index = 0; $index < $installmentCount; $index++) {
            $dueDate = $startDate->copy()->addMonths($index);
            $amount = ($index === $installmentCount - 1)
                ? round($total - $accumulated, 2)
                : $baseAmount;

            $plan[] = [
                'sequence' => $index + 1,
                'due_date' => $dueDate->format('Y-m-d'),
                'amount' => $amount,
                'status' => 'pending',
            ];

            $accumulated += $amount;
        }

        return $plan;
    }

    public function getOverdueInvoices()
    {
        return $this->invoiceRepository->getOverdueInvoices();
    }

    public function getMonthlyRevenue(Carbon $startDate, Carbon $endDate)
    {
        return $this->invoiceRepository->getRevenueBetweenDates($startDate, $endDate);
    }

    protected function calculateInvoiceTotals(array $data): array
    {
        $subtotal = 0;
        $vatTotal = 0;

        foreach ($data['items'] as $item) {
            $lineTotal = $item['qty'] * $item['unit_price'];
            $subtotal += $lineTotal;
            $vatTotal += $lineTotal * (($item['vat'] ?? 20) / 100);
        }

        return array_merge($data, [
            'subtotal' => $subtotal,
            'vat_total' => $vatTotal,
            'grand_total' => $subtotal + $vatTotal,
            'currency' => $data['currency'] ?? config('app.currency', 'TRY'),
        ]);
    }
}