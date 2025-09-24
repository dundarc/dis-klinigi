<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. SoftDeletes'i import et

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

    // Scopes
    public function scopeStatus($query, InvoiceStatus $status)
    {
        return $query->where('status', $status);
    }
}
