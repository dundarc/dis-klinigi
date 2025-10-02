<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $invoice_id
 * @property \App\Enums\PaymentMethod $method
 * @property float $amount
 * @property \Carbon\Carbon $paid_at
 * @property string|null $txn_ref
 * @property string|null $notes
 * @property-read \App\Models\Invoice $invoice
 */
class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'method',
        'amount',
        'paid_at',
        'txn_ref',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'method' => \App\Enums\PaymentMethod::class,
        ];
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}