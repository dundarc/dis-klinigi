<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockExpensePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'amount',
        'payment_date',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'payment_method' => \App\Enums\PaymentMethod::class,
    ];

    public function expense()
    {
        return $this->belongsTo(StockExpense::class, 'expense_id');
    }
}
