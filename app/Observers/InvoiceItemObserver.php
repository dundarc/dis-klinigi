<?php

namespace App\Observers;

use App\Models\InvoiceItem;
use App\Models\Invoice;

class InvoiceItemObserver
{
    public function created(InvoiceItem $invoiceItem): void
    {
        $this->recalculateInvoiceTotals($invoiceItem->invoice);
    }

    public function updated(InvoiceItem $invoiceItem): void
    {
        $this->recalculateInvoiceTotals($invoiceItem->invoice);
    }

    public function deleted(InvoiceItem $invoiceItem): void
    {
        $this->recalculateInvoiceTotals($invoiceItem->invoice);
    }

    protected function recalculateInvoiceTotals(Invoice $invoice): void
    {
        $invoice->loadMissing('items');

        $subtotal = $invoice->items->sum(fn (InvoiceItem $item) => $item->qty * $item->unit_price);
        $vatTotal = $invoice->items->sum(fn (InvoiceItem $item) => $item->qty * $item->unit_price * (($item->vat ?? 0) / 100));
        $grandTotal = $subtotal + $vatTotal;

        $invoice->forceFill([
            'subtotal' => $subtotal,
            'vat_total' => $vatTotal,
            'grand_total' => $grandTotal,
        ])->save();
    }
}