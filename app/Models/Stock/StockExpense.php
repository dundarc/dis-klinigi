<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'title',
        'expense_date',
        'amount',
        'vat_rate',
        'vat_amount',
        'total_amount',
        'payment_status',
        'payment_method',
        'due_date',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'date',
        'amount' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_method' => \App\Enums\PaymentMethod::class,
    ];

    public function category()
    {
        return $this->belongsTo(StockExpenseCategory::class, 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(StockSupplier::class, 'supplier_id');
    }

    public function payments()
    {
        return $this->hasMany(StockExpensePayment::class, 'expense_id');
    }

    public function accountMovements()
    {
        return $this->morphMany(StockAccountMovement::class, 'reference');
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments->sum('amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->total_amount - $this->total_paid;
    }

    public function updatePaymentStatus(): void
    {
        $totalPaid = $this->total_paid;
        $totalAmount = $this->total_amount;

        if ($totalPaid == 0) {
            $this->payment_status = 'pending';
        } elseif ($totalPaid >= $totalAmount) {
            $this->payment_status = 'paid';
        } else {
            $this->payment_status = 'partial';
        }

        $this->save();
    }
}
