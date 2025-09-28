<?php

namespace Database\Seeders;

use App\Models\Stock\StockExpenseCategory;
use Illuminate\Database\Seeder;

class StockExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektrik', 'slug' => 'electricity'],
            ['name' => 'Su', 'slug' => 'water'],
            ['name' => 'İnternet', 'slug' => 'internet'],
            ['name' => 'Telefon', 'slug' => 'telephone'],
            ['name' => 'Bakım ve Onarım', 'slug' => 'maintenance'],
            ['name' => 'Sarf Malzemeleri', 'slug' => 'supplies'],
            ['name' => 'Tıbbi Atık', 'slug' => 'waste_disposal'],
            ['name' => 'Temizlik', 'slug' => 'cleaning'],
            ['name' => 'Pazarlama', 'slug' => 'marketing'],
            ['name' => 'Eğitim', 'slug' => 'training'],
            ['name' => 'Sigorta', 'slug' => 'insurance'],
            ['name' => 'Diğer', 'slug' => 'other'],
        ];

        foreach ($categories as $category) {
            StockExpenseCategory::create($category);
        }
    }
}