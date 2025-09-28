<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'expense_date',
        'category',
        'payment_method',
        'status',
        'notes',
        'receipt_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'status' => 'string',
    ];

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeMonthly($query, $year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        return $query->whereYear('expense_date', $year)
                    ->whereMonth('expense_date', $month);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
