<?php

namespace App\Models\Stock;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPurchaseInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'invoice_date',
        'subtotal',
        'vat_total',
        'grand_total',
        'notes',
        'payment_status',
        'payment_method',
        'due_date',
        'file_path',
        'parsed_payload',
        'payment_history',
        'payment_schedule',
        'is_installment',
        'total_installments',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'parsed_payload' => 'array',
        'payment_history' => 'array',
        'payment_schedule' => 'array',
        'is_installment' => 'boolean',
        'payment_status' => PaymentStatus::class,
        'payment_method' => \App\Enums\PaymentMethod::class,
    ];

    public function supplier()
    {
        return $this->belongsTo(StockSupplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(StockPurchaseItem::class, 'purchase_invoice_id');
    }

    public function paymentSchedules()
    {
        return $this->hasMany(StockPaymentSchedule::class, 'purchase_invoice_id');
    }

    public function accountMovements()
    {
        return $this->morphMany(StockAccountMovement::class, 'reference');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('payment_status', PaymentStatus::OVERDUE)
                    ->orWhere(function($q) {
                        $q->where('due_date', '<', now())
                          ->whereIn('payment_status', [PaymentStatus::PENDING, PaymentStatus::PARTIAL, PaymentStatus::INSTALLMENT]);
                    });
    }

    public function scopeInstallment($query)
    {
        return $query->where('is_installment', true);
    }

    public function getTotalPaidAttribute(): float
    {
        if ($this->is_installment) {
            return (float) $this->paymentSchedules()->sum('paid_amount');
        }

        if (!$this->payment_history) {
            return $this->payment_status === PaymentStatus::PAID ? (float) $this->grand_total : 0;
        }

        return collect($this->payment_history)->sum('amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return (float) $this->grand_total - $this->total_paid;
    }

    public function isOverdue(): bool
    {
        if ($this->payment_status === PaymentStatus::PAID) {
            return false;
        }

        if ($this->is_installment) {
            return $this->paymentSchedules()->where('due_date', '<', now())
                       ->where('status', '!=', PaymentStatus::PAID)
                       ->exists();
        }

        return $this->due_date && $this->due_date < now() && 
               $this->payment_status !== PaymentStatus::PAID;
    }

    public function getNextPaymentDateAttribute()
    {
        if (!$this->is_installment) {
            return $this->due_date;
        }

        return $this->paymentSchedules()
                   ->where('status', '!=', PaymentStatus::PAID)
                   ->orderBy('due_date')
                   ->first()?->due_date;
    }

    public function updatePaymentStatus(): void
    {
        if ($this->is_installment) {
            $schedules = $this->paymentSchedules;
            $totalSchedules = $schedules->count();
            $paidSchedules = $schedules->where('status', PaymentStatus::PAID)->count();
            
            if ($paidSchedules === $totalSchedules) {
                $this->payment_status = PaymentStatus::PAID;
            } elseif ($paidSchedules > 0) {
                $this->payment_status = PaymentStatus::PARTIAL;
            } elseif ($this->isOverdue()) {
                $this->payment_status = PaymentStatus::OVERDUE;
            } else {
                $this->payment_status = PaymentStatus::INSTALLMENT;
            }
        } else {
            $totalPaid = $this->total_paid;
            
            if ($totalPaid >= $this->grand_total) {
                $this->payment_status = PaymentStatus::PAID;
            } elseif ($totalPaid > 0) {
                $this->payment_status = PaymentStatus::PARTIAL;
            } elseif ($this->isOverdue()) {
                $this->payment_status = PaymentStatus::OVERDUE;
            } else {
                $this->payment_status = PaymentStatus::PENDING;
            }
        }

        $this->save();
    }

    public function createInstallmentSchedule(int $installments, ?array $customAmounts = null): void
    {
        $this->is_installment = true;
        $this->total_installments = $installments;
        $this->save();

        $perInstallment = $customAmounts ? $customAmounts : 
                         array_fill(0, $installments, round($this->grand_total / $installments, 2));
        
        // Adjust last installment to match exact total
        if (!$customAmounts) {
            $totalCalculated = array_sum($perInstallment);
            $difference = $this->grand_total - $totalCalculated;
            $perInstallment[$installments - 1] += $difference;
        }

        $baseDate = $this->due_date ?: $this->invoice_date->addMonth();
        
        for ($i = 0; $i < $installments; $i++) {
            $this->paymentSchedules()->create([
                'installment_number' => $i + 1,
                'amount' => $perInstallment[$i],
                'due_date' => $baseDate->copy()->addMonths($i),
                'status' => PaymentStatus::PENDING,
            ]);
        }

        $this->updatePaymentStatus();
    }
}
