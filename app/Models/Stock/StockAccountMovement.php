<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAccountMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'direction',
        'amount',
        'movement_date',
        'payment_method',
        'reference_type',
        'reference_id',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'movement_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(StockSupplier::class, 'supplier_id');
    }

    public function reference()
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }

    public function scopeDebit($query)
    {
        return $query->where('direction', 'debit');
    }

    public function scopeCredit($query)
    {
        return $query->where('direction', 'credit');
    }
}
