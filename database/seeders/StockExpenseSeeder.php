<?php

namespace Database\Seeders;

use App\Models\Stock\StockExpense;
use App\Models\Stock\StockSupplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockExpenseSeeder extends Seeder
{
    public function run(): void
    {
        // Get suppliers, categories and user
        $suppliers = StockSupplier::all();
        $categories = \App\Models\Stock\StockExpenseCategory::all();
        $user = User::first();

        $expenses = [
            [
                'supplier' => $suppliers->where('name', 'Teknik Servis A.Ş.')->first(),
                'title' => 'Diş ünitesi yıllık bakım',
                'amount' => 2500.00,
                'expense_date' => now()->subDays(45),
                'payment_method' => 'bank_transfer',
                'category_id' => $categories->where('slug', 'maintenance')->first()?->id,
                'notes' => 'Diş ünitesi ve aspiratör sistemi yıllık bakımı',
            ],
            [
                'supplier' => $suppliers->where('name', 'Teknik Servis A.Ş.')->first(),
                'title' => 'X-Ray cihazı kalibrasyonu',
                'amount' => 800.00,
                'expense_date' => now()->subDays(30),
                'payment_method' => 'cash',
                'category_id' => $categories->where('slug', 'maintenance')->first()?->id,
                'notes' => 'Panoramik X-ray cihazı kalibrasyon ve kontrolü',
            ],
            [
                'supplier' => $suppliers->where('name', 'Dürr Dental')->first(),
                'title' => 'Aspiratör filtresi değişimi',
                'amount' => 450.00,
                'expense_date' => now()->subDays(20),
                'payment_method' => 'credit_card',
                'category_id' => $categories->where('slug', 'supplies')->first()?->id,
                'notes' => 'Aspiratör sistemi HEPA filtresi ve karbon filtresi',
            ],
            [
                'supplier' => null, // No supplier for utilities
                'title' => 'Elektrik faturası',
                'amount' => 1250.00,
                'expense_date' => now()->subDays(15),
                'payment_method' => 'bank_transfer',
                'category_id' => $categories->where('slug', 'utilities')->first()?->id,
                'notes' => 'Klinik elektrik tüketimi - Şubat ayı',
            ],
            [
                'supplier' => null,
                'title' => 'Su faturası',
                'amount' => 180.00,
                'expense_date' => now()->subDays(15),
                'payment_method' => 'bank_transfer',
                'category_id' => $categories->where('slug', 'utilities')->first()?->id,
                'notes' => 'Klinik su tüketimi - Şubat ayı',
            ],
            [
                'supplier' => null,
                'title' => 'İnternet ve telefon',
                'amount' => 320.00,
                'expense_date' => now()->subDays(10),
                'payment_method' => 'bank_transfer',
                'category_id' => $categories->where('slug', 'utilities')->first()?->id,
                'notes' => 'Fiber internet ve sabit telefon hattı',
            ],
            [
                'supplier' => $suppliers->where('name', 'Medicadent')->first(),
                'title' => 'Tıbbi atık bertaraf',
                'amount' => 95.00,
                'expense_date' => now()->subDays(8),
                'payment_method' => 'cash',
                'category_id' => $categories->where('slug', 'waste_disposal')->first()?->id,
                'notes' => 'Tıbbi atık toplama ve bertaraf ücreti',
            ],
            [
                'supplier' => null,
                'title' => 'Temizlik malzemeleri',
                'amount' => 150.00,
                'expense_date' => now()->subDays(5),
                'payment_method' => 'cash',
                'category_id' => $categories->where('slug', 'cleaning')->first()?->id,
                'notes' => 'Klinik temizlik malzemeleri ve dezenfektanlar',
            ],
            [
                'supplier' => $suppliers->where('name', 'NSK Dental')->first(),
                'title' => 'El aleti bakımı',
                'amount' => 120.00,
                'expense_date' => now()->subDays(3),
                'payment_method' => 'cash',
                'category_id' => $categories->where('slug', 'maintenance')->first()?->id,
                'notes' => 'Tartar skaler ve el aletleri bakımı',
            ],
            [
                'supplier' => null,
                'title' => 'Çöp poşeti ve hijyen malzemeleri',
                'amount' => 45.00,
                'expense_date' => now()->subDays(2),
                'payment_method' => 'cash',
                'category_id' => $categories->where('slug', 'supplies')->first()?->id,
                'notes' => 'Klinik hijyen malzemeleri',
            ],
        ];

        foreach ($expenses as $expenseData) {
            StockExpense::create([
                'supplier_id' => $expenseData['supplier']?->id,
                'category_id' => $expenseData['category_id'],
                'title' => $expenseData['title'],
                'amount' => $expenseData['amount'],
                'expense_date' => $expenseData['expense_date'],
                'payment_method' => $expenseData['payment_method'],
                'notes' => $expenseData['notes'],
            ]);
        }
    }
}