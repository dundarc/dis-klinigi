<?php

namespace App\Models\Stock;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockPaymentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_invoice_id',
        'installment_number',
        'amount',
        'due_date',
        'paid_amount',
        'status',
        'paid_date',
        'payment_method',
        'notes',
        'receipt_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'status' => PaymentStatus::class,
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(StockPurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function getRemainingAmountAttribute(): float
    {
        return (float)$this->amount - (float)$this->paid_amount;
    }

    public function isOverdue(): bool
    {
        return $this->due_date < now()->toDateString() && $this->status !== PaymentStatus::PAID;
    }

    public function isPaid(): bool
    {
        return $this->status === PaymentStatus::PAID || $this->paid_amount >= $this->amount;
    }

    public function markAsPaid(float $amount, string $method = null, string $notes = null, string $receiptPath = null): void
    {
        $this->update([
            'paid_amount' => $amount,
            'status' => PaymentStatus::PAID,
            'paid_date' => now(),
            'payment_method' => $method,
            'notes' => $notes,
            'receipt_path' => $receiptPath,
        ]);
    }

    public function addPartialPayment(float $amount, string $method = null, string $notes = null, string $receiptPath = null): void
    {
        $newPaidAmount = (float)$this->paid_amount + $amount;
        
        $this->update([
            'paid_amount' => $newPaidAmount,
            'status' => $newPaidAmount >= $this->amount ? PaymentStatus::PAID : PaymentStatus::PARTIAL,
            'paid_date' => $newPaidAmount >= $this->amount ? now() : $this->paid_date,
            'payment_method' => $method ?? $this->payment_method,
            'notes' => $notes ?? $this->notes,
            'receipt_path' => $receiptPath ?? $this->receipt_path,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function (StockPaymentSchedule $schedule) {
            // Update invoice payment status when installment status changes
            $schedule->invoice->updatePaymentStatus();
        });
    }
}