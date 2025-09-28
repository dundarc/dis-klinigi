<?php

namespace Database\Seeders;

use App\Models\Stock\StockItem;
use App\Models\Stock\StockUsage;
use App\Models\Stock\StockUsageItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockUsageSeeder extends Seeder
{
    public function run(): void
    {
        $items = StockItem::all();
        $user = User::first();

        $usageRecords = [
            [
                'used_at' => now()->subDays(5),
                'notes' => 'Günlük tedavi malzemeleri kullanımı - Günlük kompozit ve bonding kullanımı',
                'items' => [
                    ['item' => $items->where('name', 'Kompozit Dolgu (A2 Shade)')->first(), 'quantity' => 2],
                    ['item' => $items->where('name', 'Bonding Ajanı')->first(), 'quantity' => 1],
                    ['item' => $items->where('name', 'Latex Eldiven (Orta Boy)')->first(), 'quantity' => 5],
                    ['item' => $items->where('name', 'Cerrahi Maske')->first(), 'quantity' => 3],
                    ['item' => $items->where('name', 'Steril Örtü (Mavi)')->first(), 'quantity' => 4],
                ],
            ],
            [
                'used_at' => now()->subDays(4),
                'notes' => 'Endodontik tedavi malzemeleri - Kanal tedavisi malzemeleri',
                'items' => [
                    ['item' => $items->where('name', 'Kanal Dolgu Materyali (AH Plus)')->first(), 'quantity' => 1],
                    ['item' => $items->where('name', 'Kanal Aleti Seti')->first(), 'quantity' => 1],
                    ['item' => $items->where('name', 'Latex Eldiven (Orta Boy)')->first(), 'quantity' => 2],
                ],
            ],
            [
                'used_at' => now()->subDays(3),
                'notes' => 'Protez laboratuvar malzemeleri - Zirkonyum kron ve porselen veneer hazırlığı',
                'items' => [
                    ['item' => $items->where('name', 'Zirconia Crown Material')->first(), 'quantity' => 1],
                    ['item' => $items->where('name', 'Porcelain Veneer Kit')->first(), 'quantity' => 1],
                    ['item' => $items->where('name', 'Dental Stone Type 4')->first(), 'quantity' => 2],
                    ['item' => $items->where('name', 'Impression Material Heavy')->first(), 'quantity' => 3],
                    ['item' => $items->where('name', 'Impression Material Light')->first(), 'quantity' => 2],
                ],
            ],
            [
                'used_at' => now()->subDays(2),
                'notes' => 'Haftalık tüketim malzemeleri - Haftalık sarf malzemeleri kullanımı',
                'items' => [
                    ['item' => $items->where('name', 'Sterile Gloves Medium')->first(), 'quantity' => 15],
                    ['item' => $items->where('name', 'Sterile Gloves Large')->first(), 'quantity' => 12],
                    ['item' => $items->where('name', 'Face Masks')->first(), 'quantity' => 20],
                    ['item' => $items->where('name', 'Dental Bibs')->first(), 'quantity' => 25],
                    ['item' => $items->where('name', 'Dental Floss')->first(), 'quantity' => 8],
                ],
            ],
            [
                'used_at' => now()->subDays(1),
                'notes' => 'Kompozit dolgu malzemeleri - Çoklu kompozit dolguları',
                'items' => [
                    ['item' => $items->where('name', 'Light Cure Composite Kit')->first(), 'quantity' => 1],
                    ['item' => $items->where('name', 'Bonding Agent')->first(), 'quantity' => 2],
                    ['item' => $items->where('name', 'Glass Ionomer Cement')->first(), 'quantity' => 1],
                    ['item' => $items->where('name', 'Sterile Gloves Medium')->first(), 'quantity' => 3],
                ],
            ],
        ];

        foreach ($usageRecords as $usageData) {
            $usage = StockUsage::create([
                'recorded_by' => $user->id,
                'used_at' => $usageData['used_at'],
                'notes' => $usageData['notes'],
            ]);

            foreach ($usageData['items'] as $itemData) {
                StockUsageItem::create([
                    'usage_id' => $usage->id,
                    'stock_item_id' => $itemData['item']->id,
                    'quantity' => $itemData['quantity'],
                ]);

                // Decrease stock quantity
                $itemData['item']->decrement('quantity', $itemData['quantity']);
            }
        }
    }
}