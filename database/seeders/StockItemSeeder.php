<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StockItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get existing categories
        $categories = DB::table('stock_categories')->pluck('id', 'name')->toArray();

        $stockItems = [
            // Tıbbi Sarf Malzemeleri
            [
                'name' => 'Latex Eldiven (Orta Boy)',
                'category' => 'Tıbbi Sarf Malzemeleri',
                'unit' => 'adet',
                'minimum_quantity' => 100,
                'quantity' => $faker->numberBetween(50, 500),
            ],
            [
                'name' => 'Cerrahi Maske',
                'category' => 'Tıbbi Sarf Malzemeleri',
                'unit' => 'adet',
                'minimum_quantity' => 200,
                'quantity' => $faker->numberBetween(100, 1000),
            ],
            [
                'name' => 'Steril Örtü (Mavi)',
                'category' => 'Tıbbi Sarf Malzemeleri',
                'unit' => 'adet',
                'minimum_quantity' => 50,
                'quantity' => $faker->numberBetween(20, 200),
            ],

            // Diş Dolgu Malzemeleri
            [
                'name' => 'Kompozit Dolgu (A2 Shade)',
                'category' => 'Diş Dolgu Malzemeleri',
                'unit' => 'şırınga',
                'minimum_quantity' => 5,
                'quantity' => $faker->numberBetween(2, 20),
            ],
            [
                'name' => 'Bonding Ajanı',
                'category' => 'Diş Dolgu Malzemeleri',
                'unit' => 'şişe',
                'minimum_quantity' => 3,
                'quantity' => $faker->numberBetween(1, 10),
            ],
            [
                'name' => 'Kompozit Cilalama Seti',
                'category' => 'Diş Dolgu Malzemeleri',
                'unit' => 'set',
                'minimum_quantity' => 2,
                'quantity' => $faker->numberBetween(1, 8),
            ],

            // İmplant ve Protez
            [
                'name' => 'Dental İmplant (4.1mm)',
                'category' => 'İmplant ve Protez',
                'unit' => 'adet',
                'minimum_quantity' => 2,
                'quantity' => $faker->numberBetween(0, 10),
            ],
            [
                'name' => 'Abutment (Standart)',
                'category' => 'İmplant ve Protez',
                'unit' => 'adet',
                'minimum_quantity' => 5,
                'quantity' => $faker->numberBetween(2, 15),
            ],

            // Anestezi ve Sedasyon
            [
                'name' => 'Lidokain %2 (Kartuş)',
                'category' => 'Anestezi ve Sedasyon',
                'unit' => 'kartuş',
                'minimum_quantity' => 20,
                'quantity' => $faker->numberBetween(10, 100),
            ],
            [
                'name' => 'Anestezi İğnesi (27G)',
                'category' => 'Anestezi ve Sedasyon',
                'unit' => 'adet',
                'minimum_quantity' => 50,
                'quantity' => $faker->numberBetween(20, 200),
            ],

            // Endodonti Malzemeleri
            [
                'name' => 'Kanal Dolgu Materyali (AH Plus)',
                'category' => 'Endodonti Malzemeleri',
                'unit' => 'şırınga',
                'minimum_quantity' => 3,
                'quantity' => $faker->numberBetween(1, 10),
            ],
            [
                'name' => 'Kanal Aleti Seti',
                'category' => 'Endodonti Malzemeleri',
                'unit' => 'set',
                'minimum_quantity' => 1,
                'quantity' => $faker->numberBetween(0, 5),
            ],

            // Periodonti Malzemeleri
            [
                'name' => 'Kürtaj Aleti Seti',
                'category' => 'Periodonti Malzemeleri',
                'unit' => 'set',
                'minimum_quantity' => 2,
                'quantity' => $faker->numberBetween(1, 6),
            ],
            [
                'name' => 'Diş Taşı Temizleme Tozu',
                'category' => 'Periodonti Malzemeleri',
                'unit' => 'kg',
                'minimum_quantity' => 1,
                'quantity' => $faker->numberBetween(0.5, 5),
            ],

            // Ortodonti Malzemeleri
            [
                'name' => 'Metal Braket',
                'category' => 'Ortodonti Malzemeleri',
                'unit' => 'adet',
                'minimum_quantity' => 10,
                'quantity' => $faker->numberBetween(5, 50),
            ],
            [
                'name' => 'Ortodontik Tel (0.016")',
                'category' => 'Ortodonti Malzemeleri',
                'unit' => 'paket',
                'minimum_quantity' => 5,
                'quantity' => $faker->numberBetween(2, 20),
            ],

            // Röntgen Malzemeleri
            [
                'name' => 'Periapikal Film',
                'category' => 'Röntgen Malzemeleri',
                'unit' => 'adet',
                'minimum_quantity' => 50,
                'quantity' => $faker->numberBetween(20, 200),
            ],
            [
                'name' => 'Röntgen Fikstür',
                'category' => 'Röntgen Malzemeleri',
                'unit' => 'adet',
                'minimum_quantity' => 2,
                'quantity' => $faker->numberBetween(1, 8),
            ],

            // Sterilizasyon
            [
                'name' => 'Sterilizasyon Poşeti (S)',
                'category' => 'Sterilizasyon',
                'unit' => 'adet',
                'minimum_quantity' => 100,
                'quantity' => $faker->numberBetween(50, 500),
            ],
            [
                'name' => 'Oto Klav Biyolojik İndikatör',
                'category' => 'Sterilizasyon',
                'unit' => 'adet',
                'minimum_quantity' => 10,
                'quantity' => $faker->numberBetween(5, 50),
            ],

            // Ofis Malzemeleri
            [
                'name' => 'A4 Kağıt',
                'category' => 'Ofis Malzemeleri',
                'unit' => 'paket',
                'minimum_quantity' => 2,
                'quantity' => $faker->numberBetween(1, 10),
            ],
            [
                'name' => 'Hasta Dosyası',
                'category' => 'Ofis Malzemeleri',
                'unit' => 'adet',
                'minimum_quantity' => 20,
                'quantity' => $faker->numberBetween(10, 100),
            ],

            // Temizlik Malzemeleri
            [
                'name' => 'El Dezenfektanı',
                'category' => 'Temizlik Malzemeleri',
                'unit' => 'litre',
                'minimum_quantity' => 5,
                'quantity' => $faker->numberBetween(2, 20),
            ],
            [
                'name' => 'Yüzey Temizleyici',
                'category' => 'Temizlik Malzemeleri',
                'unit' => 'litre',
                'minimum_quantity' => 3,
                'quantity' => $faker->numberBetween(1, 15),
            ],
        ];

        foreach ($stockItems as $item) {
            if (!isset($categories[$item['category']])) {
                continue; // Skip if category doesn't exist
            }

            DB::table('stock_items')->insert([
                'name' => $item['name'],
                'sku' => $this->generateSKU($item['name']),
                'barcode' => $faker->ean13,
                'category_id' => $categories[$item['category']],
                'unit' => $item['unit'],
                'minimum_quantity' => $item['minimum_quantity'],
                'quantity' => $item['quantity'],
                'allow_negative' => false,
                'is_active' => true,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }

        // Add some additional random items
        for ($i = 0; $i < 50; $i++) {
            $categoryId = $faker->randomElement(array_values($categories));
            $units = ['adet', 'paket', 'şişe', 'şırınga', 'set', 'kg', 'litre', 'kutu'];

            DB::table('stock_items')->insert([
                'name' => $faker->randomElement([
                    'Diş Fırçası', 'Diş Macunu', 'Ağız Çalkalama Suyu', 'Florid Jel',
                    'Diş İpi', 'Ağız Spreyi', 'Ortodontik Bant', 'Kompozit Bond',
                    'Cam İonomer', 'Geçici Dolgu', 'Diş Beyazlatma Jeli', 'Ağrı Kesici',
                    'Antibiyotik', 'Vitamin', 'Kalsiyum Takviyesi'
                ]),
                'sku' => $this->generateSKU($faker->word),
                'barcode' => $faker->ean13,
                'category_id' => $categoryId,
                'unit' => $faker->randomElement($units),
                'minimum_quantity' => $faker->numberBetween(1, 20),
                'quantity' => $faker->numberBetween(0, 100),
                'allow_negative' => false,
                'is_active' => $faker->boolean(95),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }

    private function generateSKU(string $name): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));
        $number = rand(1000, 9999);
        return $prefix . $number;
    }
}