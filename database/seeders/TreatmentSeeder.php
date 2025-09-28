<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        $treatments = [
            // Diş Temizliği ve Bakım
            [
                'code' => 'DT001',
                'name' => 'Diş Taşı Temizliği (Detertraj)',
                'default_price' => 800.00,
                'default_vat' => 20.00,
                'default_duration_min' => 60,
                'description' => 'Profesyonel diş taşı temizliği ve cilalama işlemi'
            ],
            [
                'code' => 'DT002',
                'name' => 'Diş Temizliği ve Cilalama',
                'default_price' => 400.00,
                'default_vat' => 20.00,
                'default_duration_min' => 45,
                'description' => 'Diş yüzeylerinin temizlenmesi ve cilalanması'
            ],

            // Dolgu İşlemleri
            [
                'code' => 'DG001',
                'name' => 'Kompozit Dolgu (Tek Yüzlü)',
                'default_price' => 1200.00,
                'default_vat' => 20.00,
                'default_duration_min' => 45,
                'description' => 'Tek yüzlü kompozit dolgu uygulaması'
            ],
            [
                'code' => 'DG002',
                'name' => 'Kompozit Dolgu (Çift Yüzlü)',
                'default_price' => 1500.00,
                'default_vat' => 20.00,
                'default_duration_min' => 60,
                'description' => 'Çift yüzlü kompozit dolgu uygulaması'
            ],
            [
                'code' => 'DG003',
                'name' => 'Kompozit Dolgu (Üç Yüzlü)',
                'default_price' => 1800.00,
                'default_vat' => 20.00,
                'default_duration_min' => 75,
                'description' => 'Üç yüzlü kompozit dolgu uygulaması'
            ],

            // Kuron ve Kaplama
            [
                'code' => 'KK001',
                'name' => 'Porselen Kuron (Zirkonyum)',
                'default_price' => 3500.00,
                'default_vat' => 20.00,
                'default_duration_min' => 120,
                'description' => 'Zirkonyum altyapılı porselen kuron'
            ],
            [
                'code' => 'KK002',
                'name' => 'Porselen Kuron (Metal)',
                'default_price' => 2500.00,
                'default_vat' => 20.00,
                'default_duration_min' => 120,
                'description' => 'Metal altyapılı porselen kuron'
            ],
            [
                'code' => 'KK003',
                'name' => 'Tam Seramik Kuron',
                'default_price' => 4000.00,
                'default_vat' => 20.00,
                'default_duration_min' => 120,
                'description' => 'Tam seramik kuron uygulaması'
            ],

            // Kanal Tedavisi
            [
                'code' => 'KT001',
                'name' => 'Kanal Tedavisi (Tek Kanal)',
                'default_price' => 2000.00,
                'default_vat' => 20.00,
                'default_duration_min' => 90,
                'description' => 'Tek kanal kök kanal tedavisi'
            ],
            [
                'code' => 'KT002',
                'name' => 'Kanal Tedavisi (Çift Kanal)',
                'default_price' => 2800.00,
                'default_vat' => 20.00,
                'default_duration_min' => 120,
                'description' => 'Çift kanal kök kanal tedavisi'
            ],
            [
                'code' => 'KT003',
                'name' => 'Kanal Tedavisi (Üç Kanal)',
                'default_price' => 3500.00,
                'default_vat' => 20.00,
                'default_duration_min' => 150,
                'description' => 'Üç kanal kök kanal tedavisi'
            ],

            // Diş Çekimi
            [
                'code' => 'DC001',
                'name' => 'Diş Çekimi (Basit)',
                'default_price' => 900.00,
                'default_vat' => 20.00,
                'default_duration_min' => 30,
                'description' => 'Basit diş çekimi işlemi'
            ],
            [
                'code' => 'DC002',
                'name' => 'Diş Çekimi (Cerrahi)',
                'default_price' => 1800.00,
                'default_vat' => 20.00,
                'default_duration_min' => 60,
                'description' => 'Cerrahi diş çekimi işlemi'
            ],
            [
                'code' => 'DC003',
                'name' => 'Yirmilik Diş Çekimi',
                'default_price' => 2500.00,
                'default_vat' => 20.00,
                'default_duration_min' => 90,
                'description' => 'Yirmilik diş çekimi işlemi'
            ],

            // İmplant
            [
                'code' => 'IM001',
                'name' => 'Dental İmplant',
                'default_price' => 8000.00,
                'default_vat' => 20.00,
                'default_duration_min' => 180,
                'description' => 'Tek diş implant uygulaması'
            ],
            [
                'code' => 'IM002',
                'name' => 'İmplant Üstü Protez',
                'default_price' => 12000.00,
                'default_vat' => 20.00,
                'default_duration_min' => 240,
                'description' => 'İmplant üstü protez uygulaması'
            ],

            // Ortodonti
            [
                'code' => 'OR001',
                'name' => 'Braket (Tek Çene)',
                'default_price' => 15000.00,
                'default_vat' => 20.00,
                'default_duration_min' => 30,
                'description' => 'Tek çene braket uygulaması'
            ],
            [
                'code' => 'OR002',
                'name' => 'Braket (Çift Çene)',
                'default_price' => 25000.00,
                'default_vat' => 20.00,
                'default_duration_min' => 45,
                'description' => 'Çift çene braket uygulaması'
            ],

            // Beyazlatma
            [
                'code' => 'BY001',
                'name' => 'Diş Beyazlatma (Ofis Tipi)',
                'default_price' => 3500.00,
                'default_vat' => 20.00,
                'default_duration_min' => 90,
                'description' => 'Klinik ortamında diş beyazlatma'
            ],
            [
                'code' => 'BY002',
                'name' => 'Diş Beyazlatma (Ev Tipi)',
                'default_price' => 2000.00,
                'default_vat' => 20.00,
                'default_duration_min' => 30,
                'description' => 'Evde kullanılan diş beyazlatma kiti'
            ],

            // Muayene ve Teşhis
            [
                'code' => 'MT001',
                'name' => 'Muayene ve Teşhis',
                'default_price' => 500.00,
                'default_vat' => 20.00,
                'default_duration_min' => 30,
                'description' => 'Genel muayene ve teşhis'
            ],
            [
                'code' => 'MT002',
                'name' => 'Acil Muayene',
                'default_price' => 800.00,
                'default_vat' => 20.00,
                'default_duration_min' => 45,
                'description' => 'Acil durum muayenesi'
            ],

            // Diğer Tedaviler
            [
                'code' => 'DG004',
                'name' => 'Geçici Dolgu',
                'default_price' => 300.00,
                'default_vat' => 20.00,
                'default_duration_min' => 15,
                'description' => 'Geçici dolgu uygulaması'
            ],
            [
                'code' => 'PR001',
                'name' => 'Reçete Yazımı',
                'default_price' => 200.00,
                'default_vat' => 20.00,
                'default_duration_min' => 10,
                'description' => 'İlaç reçetesi yazımı'
            ],
            [
                'code' => 'RT001',
                'name' => 'Röntgen Çekimi',
                'default_price' => 150.00,
                'default_vat' => 20.00,
                'default_duration_min' => 10,
                'description' => 'Tek diş röntgeni'
            ],
            [
                'code' => 'RT002',
                'name' => 'Panoramik Röntgen',
                'default_price' => 300.00,
                'default_vat' => 20.00,
                'default_duration_min' => 15,
                'description' => 'Panoramik çene röntgeni'
            ],
        ];

        foreach ($treatments as $treatment) {
            DB::table('treatments')->insert([
                'code' => $treatment['code'],
                'name' => $treatment['name'],
                'default_price' => $treatment['default_price'],
                'default_vat' => $treatment['default_vat'],
                'default_duration_min' => $treatment['default_duration_min'],
                'description' => $treatment['description'],
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}