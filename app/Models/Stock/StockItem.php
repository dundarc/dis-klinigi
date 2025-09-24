<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'category_id',
        'unit',
        'minimum_quantity',
        'quantity',
        'allow_negative',
        'is_active',
    ];

    protected $casts = [
        'minimum_quantity' => 'decimal:2',
        'quantity' => 'decimal:2',
        'allow_negative' => 'bool',
        'is_active' => 'bool',
    ];

    public function category()
    {
        return $this->belongsTo(StockCategory::class, 'category_id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(StockPurchaseItem::class, 'stock_item_id');
    }

    public function usageItems()
    {
        return $this->hasMany(StockUsageItem::class, 'stock_item_id');
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'stock_item_id');
    }

    public function isBelowMinimum(): bool
    {
        if ($this->minimum_quantity <= 0) {
            return false;
        }

        return (float) $this->quantity < (float) $this->minimum_quantity;
    }
}
