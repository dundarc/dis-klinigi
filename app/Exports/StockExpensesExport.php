<?php

namespace App\Exports;

use App\Models\Stock\StockExpense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockExpensesExport implements FromCollection, WithHeadings, WithMapping
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
        $query = StockExpense::with(['category', 'supplier']);

        if ($this->query) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->query . '%')
                  ->orWhereHas('category', function ($catQuery) {
                      $catQuery->where('name', 'like', '%' . $this->query . '%');
                  })
                  ->orWhereHas('supplier', function ($supQuery) {
                      $supQuery->where('name', 'like', '%' . $this->query . '%');
                  });
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Gider Adı',
            'Kategori',
            'Tedarikçi',
            'Tutar (TL)',
            'KDV Oranı (%)',
            'Toplam Tutar (TL)',
            'Tarih',
            'Vade Tarihi',
            'Ödeme Yöntemi',
            'Ödeme Durumu',
            'Açıklama',
        ];
    }

    public function map($expense): array
    {
        return [
            $expense->title,
            $expense->category?->name ?? 'Kategori Yok',
            $expense->supplier?->name ?? 'Tedarikçi Yok',
            $expense->amount,
            $expense->vat_rate,
            $expense->total_amount,
            $expense->expense_date->format('d.m.Y'),
            $expense->due_date?->format('d.m.Y') ?? '',
            $expense->payment_method ?? '',
            $expense->payment_status === 'paid' ? 'Ödendi' : ($expense->payment_status === 'pending' ? 'Bekliyor' : 'Gecikmiş'),
            $expense->notes ?? '',
        ];
    }
}
