<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StockSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        $suppliers = [
            [
                'name' => 'Medikal Tedarik A.Ş.',
                'phone' => '+90 216 555 01 01',
                'email' => 'info@medikaltedarik.com',
                'address' => 'İstanbul Cad. No:123, Üsküdar, İstanbul',
                'tax_number' => '12345678901',
                'contact_person' => 'Ahmet Yılmaz',
            ],
            [
                'name' => 'Diş Sağlık Malzemeleri Ltd.',
                'phone' => '+90 216 555 02 02',
                'email' => 'satis@dishekimi.com',
                'address' => 'Ankara Bulvarı No:456, Çankaya, Ankara',
                'tax_number' => '23456789012',
                'contact_person' => 'Ayşe Kaya',
            ],
            [
                'name' => 'İzmir Medikal',
                'phone' => '+90 232 555 03 03',
                'email' => 'iletisim@izmirmedikal.com',
                'address' => 'İzmir Sokak No:789, Konak, İzmir',
                'tax_number' => '34567890123',
                'contact_person' => 'Mehmet Demir',
            ],
            [
                'name' => 'Bursa Sağlık Ürünleri',
                'phone' => '+90 224 555 04 04',
                'email' => 'info@bursasaglik.com',
                'address' => 'Bursa Caddesi No:101, Nilüfer, Bursa',
                'tax_number' => '45678901234',
                'contact_person' => 'Zeynep Çelik',
            ],
            [
                'name' => 'Antalya Medikal Distribütör',
                'phone' => '+90 242 555 05 05',
                'email' => 'satis@antalya-medikal.com',
                'address' => 'Antalya Bulvarı No:202, Muratpaşa, Antalya',
                'tax_number' => '56789012345',
                'contact_person' => 'Mustafa Şahin',
            ],
            [
                'name' => 'Konya Diş Malzemeleri',
                'phone' => '+90 332 555 06 06',
                'email' => 'info@konyadismalzeme.com',
                'address' => 'Konya Sokak No:303, Selçuklu, Konya',
                'tax_number' => '67890123456',
                'contact_person' => 'Fatma Özkan',
            ],
            [
                'name' => 'Adana Sağlık Tedarik',
                'phone' => '+90 322 555 07 07',
                'email' => 'tedarik@adanasaglik.com',
                'address' => 'Adana Caddesi No:404, Seyhan, Adana',
                'tax_number' => '78901234567',
                'contact_person' => 'Ali Koç',
            ],
            [
                'name' => 'Gaziantep Medikal',
                'phone' => '+90 342 555 08 08',
                'email' => 'satis@gaziantepmedikal.com',
                'address' => 'Gaziantep Bulvarı No:505, Şahinbey, Gaziantep',
                'tax_number' => '89012345678',
                'contact_person' => 'Emine Yıldız',
            ],
        ];

        foreach ($suppliers as $supplier) {
            DB::table('stock_suppliers')->insert([
                'name' => $supplier['name'],
                'phone' => $supplier['phone'],
                'email' => $supplier['email'],
                'address' => $supplier['address'],
                'tax_number' => $supplier['tax_number'],
                'contact_person' => $supplier['contact_person'],
                'is_active' => true,
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }

        // Add some additional random suppliers
        for ($i = 0; $i < 12; $i++) {
            $cities = ['İstanbul', 'Ankara', 'İzmir', 'Bursa', 'Antalya', 'Konya', 'Adana', 'Gaziantep'];
            $districts = ['Kadıköy', 'Çankaya', 'Konak', 'Nilüfer', 'Muratpaşa', 'Selçuklu', 'Seyhan', 'Şahinbey'];

            DB::table('stock_suppliers')->insert([
                'name' => $faker->company . ' ' . $faker->randomElement(['Medikal', 'Sağlık', 'Tedarik', 'Distribütör']),
                'phone' => $faker->phoneNumber,
                'email' => $faker->companyEmail,
                'address' => $faker->streetName . ' No:' . $faker->numberBetween(1, 500) . ', ' . $faker->randomElement($districts) . ', ' . $faker->randomElement($cities),
                'tax_number' => $faker->numerify('###########'),
                'contact_person' => $faker->name,
                'is_active' => $faker->boolean(90), // 90% active
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}