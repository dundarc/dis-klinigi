<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Enums\UserRole;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin kullanıcısı (her zaman var olmalı)
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        
        $this->call([
            TreatmentSeeder::class,
            UserSeeder::class, // Hekim, asistan, resepsiyonistleri oluşturur
            ClinicDataSeeder::class, // Hastaları, randevuları ve vakaları oluşturur
            SettingSeeder::class,
        ]);
    }
}