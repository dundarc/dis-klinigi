<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'tax_number',
        'tax_office',
        'address',
        'notes',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function purchaseInvoices()
    {
        return $this->hasMany(StockPurchaseInvoice::class, 'supplier_id');
    }

    public function expenses()
    {
        return $this->hasMany(StockExpense::class, 'supplier_id');
    }

    public function accountMovements()
    {
        return $this->hasMany(StockAccountMovement::class, 'supplier_id');
    }

    public function scopeSuppliers($query)
    {
        return $query->where('type', 'supplier');
    }

    public function scopeServices($query)
    {
        return $query->where('type', 'service');
    }
}
