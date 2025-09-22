<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Klinik detayları için varsayılan ayarları oluştur.
        // `updateOrCreate` metodu, 'key' varsa günceller, yoksa oluşturur.
        Setting::updateOrCreate(
            ['key' => 'clinic_details'],
            [
                'value' => [
                    'name' => 'Laravel Diş Kliniği',
                    'address' => 'Örnek Mah. Atatürk Cad. No:123',
                    'city' => 'İstanbul',
                    'district' => 'Kadıköy',
                    'tax_id' => '1234567890',
                    'tax_office' => 'Kadıköy Vergi Dairesi',
                    'phone' => '02161234567',
                    'email' => 'info@laraveldent.com',
                    'website' => 'https://laraveldent.com',
                ]
            ]
        );
    }
}