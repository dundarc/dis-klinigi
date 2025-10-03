<?php

namespace App\Models\Stock;

use App\Models\User;
use App\Enums\MovementDirection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_item_id',
        'direction',
        'quantity',
        'reference_type',
        'reference_id',
        'note',
        'created_by',
        'movement_date',
        'batch_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'direction' => MovementDirection::class,
        'movement_date' => 'datetime',
    ];

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class, 'stock_item_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }

    public function scopeIncoming($query)
    {
        return $query->where('direction', MovementDirection::IN);
    }

    public function scopeOutgoing($query)
    {
        return $query->where('direction', MovementDirection::OUT);
    }

    public function scopeAdjustments($query)
    {
        return $query->where('direction', MovementDirection::ADJUSTMENT);
    }

    public function scopeByDirection($query, MovementDirection $direction)
    {
        return $query->where('direction', $direction);
    }

    public function scopeByItem($query, int $itemId)
    {
        return $query->where('stock_item_id', $itemId);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('movement_date', '>=', now()->subDays($days));
    }

    // Helper methods
    public function isIncoming(): bool
    {
        return $this->direction === MovementDirection::IN;
    }

    public function isOutgoing(): bool
    {
        return $this->direction === MovementDirection::OUT;
    }

    public function isAdjustment(): bool
    {
        return $this->direction === MovementDirection::ADJUSTMENT;
    }

    public function getFormattedQuantityAttribute(): string
    {
        $prefix = $this->isOutgoing() ? '-' : '+';
        return $prefix . number_format($this->quantity, 2);
    }

    public function getReferenceDisplayAttribute(): string
    {
        if (!$this->reference) {
            return 'Manuel İşlem';
        }

        return match ($this->reference_type) {
            StockPurchaseInvoice::class => 'Fatura: ' . ($this->reference->invoice_number ?? '#' . $this->reference->id),
            'App\Models\Stock\StockUsage' => 'Kullanım: #' . $this->reference->id,
            'App\Http\Controllers\Stock\StockItemController' => 'Manuel İşlem',
            default => 'Diğer: ' . class_basename($this->reference_type)
        };
    }
}
