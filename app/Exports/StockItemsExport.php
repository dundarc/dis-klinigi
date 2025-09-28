<?php

namespace App\Exports;

use App\Models\Stock\StockItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockItemsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = StockItem::with('category');

        if ($this->query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->query . '%')
                  ->orWhere('sku', 'like', '%' . $this->query . '%')
                  ->orWhereHas('category', function ($catQuery) {
                      $catQuery->where('name', 'like', '%' . $this->query . '%');
                  });
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Stok Adı',
            'SKU Kodu',
            'Mevcut Stok',
            'Birim',
            'Minimum Stok',
            'Kategori',
            'Durum',
            'Oluşturulma Tarihi',
        ];
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->sku,
            $item->quantity,
            $item->unit,
            $item->min_stock_level,
            $item->category?->name ?? 'Kategori Yok',
            $item->is_active ? 'Aktif' : 'Pasif',
            $item->created_at->format('d.m.Y H:i'),
        ];
    }
}
