<?php

namespace Database\Seeders;

use App\Models\Stock\StockMovement;
use App\Models\Stock\StockItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = StockItem::all();
        $users = User::all();

        if ($items->isEmpty() || $users->isEmpty()) {
            return;
        }

        $movements = [
            [
                'stock_item_id' => $items->first()->id,
                'direction' => 'in',
                'quantity' => 50,
                'note' => 'Initial stock entry',
                'created_by' => $users->first()->id,
            ],
            [
                'stock_item_id' => $items->skip(1)->first()->id ?? $items->first()->id,
                'direction' => 'out',
                'quantity' => 5,
                'note' => 'Used in treatment',
                'created_by' => $users->first()->id,
            ],
            [
                'stock_item_id' => $items->skip(2)->first()->id ?? $items->first()->id,
                'direction' => 'in',
                'quantity' => 25,
                'note' => 'Restock from supplier',
                'created_by' => $users->first()->id,
            ],
        ];

        foreach ($movements as $movement) {
            StockMovement::create($movement);
        }
    }
}
