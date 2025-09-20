<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Treatment;

class TreatmentSeeder extends Seeder
{
    public function run(): void
    {
        $treatments = [
            ['code' => 'T01', 'name' => 'Diş Taşı Temizliği (Detertraj)', 'default_price' => 800, 'default_duration_min' => 30],
            ['code' => 'T02', 'name' => 'Kanal Tedavisi (Tek Kanal)', 'default_price' => 2500, 'default_duration_min' => 90],
            ['code' => 'T03', 'name' => 'Kompozit Dolgu (Tek Yüzlü)', 'default_price' => 1200, 'default_duration_min' => 45],
            ['code' => 'T04', 'name' => 'Diş Çekimi (Basit)', 'default_price' => 900, 'default_duration_min' => 30],
            ['code' => 'T05', 'name' => 'Porselen Kuron (Zirkonyum)', 'default_price' => 4500, 'default_duration_min' => 60],
            ['code' => 'T06', 'name' => 'İmplant (Cerrahi Aşama)', 'default_price' => 15000, 'default_duration_min' => 75],
            ['code' => 'T07', 'name' => 'Diş Beyazlatma (Ofis Tipi)', 'default_price' => 3500, 'default_duration_min' => 60],
            ['code' => 'T08', 'name' => 'Muayene ve Teşhis', 'default_price' => 500, 'default_duration_min' => 20],
            ['code' => 'T09', 'name' => 'Panoramik Röntgen (OPG)', 'default_price' => 400, 'default_duration_min' => 10],
            ['code' => 'T10', 'name' => 'Gece Plağı', 'default_price' => 1800, 'default_duration_min' => 25],
        ];

        foreach ($treatments as $treatment) {
            Treatment::create($treatment);
        }
    }
}