<?php

namespace App\Services\Stock;

use App\Models\Stock\StockPurchaseInvoice;
use App\Models\InvoiceLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceCancelService
{
    public function __construct(
        protected StockMovementService $movementService
    ) {}

    public function cancel(StockPurchaseInvoice $purchase, User $user, string $reason): void
    {
        DB::transaction(function () use ($purchase, $user, $reason) {
            if ($purchase->is_cancelled) {
                throw new \Exception('Fatura zaten iptal edilmiş.');
            }

            $purchase->update([
                'is_cancelled' => true,
                'cancelled_by' => $user->id,
                'cancelled_at' => now(),
                'cancel_reason' => $reason,
            ]);

            foreach ($purchase->items as $line) {
                $item = $line->stockItem;

                if ($item) {
                    $this->movementService->recordOutgoing($item, (float) $line->quantity, [
                        'reference_type' => StockPurchaseInvoice::class,
                        'reference_id' => $purchase->id,
                        'note' => 'Fatura iptali nedeniyle stoktan düşüldü',
                        'created_by' => $user->id,
                    ]);
                }
            }

            $purchase->accountMovements()->delete();

            InvoiceLog::create([
                'invoice_id' => $purchase->id,
                'action' => 'cancelled',
                'note' => $reason,
                'user_id' => $user->id,
            ]);
        });
    }
}