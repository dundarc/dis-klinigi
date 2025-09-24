<?php

namespace App\Models\Stock;

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
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'parsed_payload' => 'array',
    ];

    public function supplier()
    {
        return $this->belongsTo(StockSupplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(StockPurchaseItem::class, 'purchase_invoice_id');
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
        return $query->where('payment_status', 'overdue');
    }
}
