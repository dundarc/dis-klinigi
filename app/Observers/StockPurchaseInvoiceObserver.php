<?php

namespace App\Observers;

use App\Models\Stock\StockPurchaseInvoice;
use App\Models\Stock\StockPurchaseItem;
use App\Services\Stock\StockMovementService;
use Illuminate\Support\Facades\Log;

class StockPurchaseInvoiceObserver
{
    public function __construct(private readonly StockMovementService $movementService)
    {
    }

    /**
     * Handle the StockPurchaseInvoice "created" event.
     */
    public function created(StockPurchaseInvoice $invoice): void
    {
        $this->processInvoiceItems($invoice, 'created');
    }

    /**
     * Handle the StockPurchaseInvoice "updated" event.
     */
    public function updated(StockPurchaseInvoice $invoice): void
    {
        // Only process if invoice status or items have changed significantly
        if ($invoice->wasChanged(['subtotal', 'grand_total'])) {
            Log::info('Invoice updated, checking for item changes', [
                'invoice_id' => $invoice->id,
                'changes' => $invoice->getChanges()
            ]);
        }
    }

    /**
     * Handle the StockPurchaseInvoice "deleting" event.
     */
    public function deleting(StockPurchaseInvoice $invoice): void
    {
        // Reverse stock movements when invoice is deleted
        $this->reverseInvoiceStockMovements($invoice);
    }

    /**
     * Process stock movements for invoice items
     */
    private function processInvoiceItems(StockPurchaseInvoice $invoice, string $event): void
    {
        try {
            foreach ($invoice->items as $item) {
                if ($item->stockItem) {
                    $this->movementService->recordIncoming(
                        $item->stockItem,
                        (float) $item->quantity,
                        [
                            'reference_type' => StockPurchaseInvoice::class,
                            'reference_id' => $invoice->id,
                            'note' => "Fatura girişi: {$invoice->invoice_number} - {$item->description}",
                            'created_by' => auth()->id(),
                            'movement_date' => $invoice->invoice_date ?? now(),
                        ]
                    );

                    Log::info('Stock movement recorded for purchase', [
                        'invoice_id' => $invoice->id,
                        'item_id' => $item->stockItem->id,
                        'quantity' => $item->quantity,
                        'event' => $event
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to process stock movements for invoice', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'event' => $event
            ]);
            
            // Don't throw exception to prevent invoice creation failure
            // The movements can be manually corrected later
        }
    }

    /**
     * Reverse stock movements when invoice is deleted
     */
    private function reverseInvoiceStockMovements(StockPurchaseInvoice $invoice): void
    {
        try {
            foreach ($invoice->items as $item) {
                if ($item->stockItem) {
                    $this->movementService->recordOutgoing(
                        $item->stockItem,
                        (float) $item->quantity,
                        [
                            'reference_type' => StockPurchaseInvoice::class,
                            'reference_id' => $invoice->id,
                            'note' => "Fatura silme işlemi: {$invoice->invoice_number} - {$item->description}",
                            'created_by' => auth()->id(),
                            'movement_date' => now(),
                        ]
                    );

                    Log::info('Stock movement reversed for deleted invoice', [
                        'invoice_id' => $invoice->id,
                        'item_id' => $item->stockItem->id,
                        'quantity' => $item->quantity
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to reverse stock movements for deleted invoice', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}