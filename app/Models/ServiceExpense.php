<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_provider',
        'service_type',
        'invoice_number',
        'amount',
        'invoice_date',
        'due_date',
        'payment_date',
        'payment_method',
        'status',
        'notes',
        'invoice_path',
        'payment_history',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'payment_history' => 'array',
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
        return $query->where('status', 'overdue')
                    ->orWhere(function ($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', now());
                    });
    }

    public function scopeMonthly($query, $year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        return $query->whereYear('invoice_date', $year)
                    ->whereMonth('invoice_date', $month);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('service_type', $type);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->due_date && $this->due_date->isPast();
    }

    public function getTotalPaidAttribute(): float
    {
        if (!$this->payment_history) {
            return $this->payment_date ? (float) $this->amount : 0;
        }

        return collect($this->payment_history)->sum('amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return (float) $this->amount - $this->total_paid;
    }
}
