<?php
namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'invoice_no', 'issue_date', 'subtotal', 'vat_total',
        'discount_total', 'grand_total', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'status' => InvoiceStatus::class,
            'subtotal' => 'decimal:2',
            'vat_total' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'grand_total' => 'decimal:2',
        ];
    }

    // Relationships
    public function patient() { return $this->belongsTo(Patient::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}