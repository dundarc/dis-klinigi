<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. SoftDeletes'i import et

/**
 * @property int $id
 * @property int $patient_id
 * @property string $invoice_no
 * @property \Carbon\Carbon $issue_date
 * @property float $subtotal
 * @property float $vat_total
 * @property float $discount_total
 * @property float $grand_total
 * @property InvoiceStatus $status
 * @property \Carbon\Carbon|null $due_date
 * @property string|null $notes
 * @property float $insurance_coverage_amount
 * @property string|null $payment_method
 * @property \Carbon\Carbon|null $paid_at
 * @property array|null $payment_details
 * @property string|null $currency
 * @property-read float $patient_payable_amount
 * @property-read \App\Models\Patient $patient
 * @property-read \Illuminate\Database\Eloquent\Collection $items
 * @property-read \Illuminate\Database\Eloquent\Collection $payments
 */
class Invoice extends Model
{
    use HasFactory, SoftDeletes; // 2. SoftDeletes'i kullan

    protected $fillable = [
        'patient_id', 'invoice_no', 'issue_date', 'subtotal', 'vat_total',
        'discount_total', 'grand_total', 'status', 'notes',
        'insurance_coverage_amount',
        // 3. Yeni alanları ekle
        'payment_method',
        'due_date',
        'payment_details',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date', // Yeni alan için cast
            'status' => InvoiceStatus::class,
            'subtotal' => 'decimal:2',
            'vat_total' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'insurance_coverage_amount' => 'decimal:2',
            'payment_details' => 'array', // Yeni alan için cast
        ];
    }

    

    // Relationships
    public function patient() { return $this->belongsTo(Patient::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }

    /**
     * Get the amount payable by the patient after insurance and discounts.
     *
     * This accessor calculates the net amount the patient needs to pay
     * by subtracting insurance coverage and discounts from the grand total.
     *
     * @return float
     */
    public function getPatientPayableAmountAttribute()
    {
        $totalPaid = $this->payments()->sum('amount');
        $discount = $this->discount_total ?? 0;

        return max(0, $this->grand_total - $discount - $totalPaid);
    }

    // Scopes
    public function scopeStatus($query, InvoiceStatus $status)
    {
        return $query->where('status', $status);
    }
}
