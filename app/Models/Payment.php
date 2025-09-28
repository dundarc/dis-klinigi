<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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