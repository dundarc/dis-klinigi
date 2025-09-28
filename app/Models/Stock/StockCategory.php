<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function items()
    {
        return $this->hasMany(StockItem::class, 'category_id');
    }

    public function isMedicalSupplies(): bool
    {
        return strtolower($this->slug) === 'saglik-malzemeleri';
    }

    public function canBeDeleted(): bool
    {
        return !$this->isMedicalSupplies();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = \Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = \Str::slug($category->name);
            }
        });
    }
}
