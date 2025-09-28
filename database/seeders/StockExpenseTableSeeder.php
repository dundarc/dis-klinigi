<?php

namespace Database\Seeders;

use App\Models\Stock\StockExpense;
use App\Models\Stock\StockExpenseCategory;
use App\Models\Stock\StockSupplier;
use Illuminate\Database\Seeder;

class StockExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = StockExpenseCategory::all();
        $suppliers = StockSupplier::all();

        if ($categories->isEmpty()) {
            return;
        }

        $expenses = [
            [
                'category_id' => $categories->first()->id,
                'supplier_id' => $suppliers->where('type', 'service')->first()?->id,
                'title' => 'Equipment Maintenance',
                'expense_date' => now()->subDays(10),
                'amount' => 500.00,
                'vat_rate' => 18,
                'vat_amount' => 90.00,
                'total_amount' => 590.00,
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'notes' => 'Monthly equipment maintenance',
            ],
            [
                'category_id' => $categories->skip(1)->first()?->id ?? $categories->first()->id,
                'supplier_id' => null,
                'title' => 'Office Supplies',
                'expense_date' => now()->subDays(5),
                'amount' => 150.00,
                'vat_rate' => 8,
                'vat_amount' => 12.00,
                'total_amount' => 162.00,
                'payment_status' => 'pending',
                'payment_method' => 'cash',
                'notes' => 'Stationery and office supplies',
            ],
        ];

        foreach ($expenses as $expense) {
            StockExpense::create($expense);
        }
    }
}
