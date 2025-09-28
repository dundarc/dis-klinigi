<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Admin user
        DB::table('users')->insert([
            'name' => 'Sistem Yöneticisi',
            'email' => 'admin@dishekimi.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+90 555 123 45 67',
            'role' => 'admin',
            'locale' => 'tr',
            'dark_mode' => false,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Dentists
        $dentistNames = [
            'Dr. Ahmet Yılmaz',
            'Dr. Mehmet Demir',
            'Dr. Ayşe Kaya',
            'Dr. Zeynep Çelik',
            'Dr. Mustafa Şahin',
            'Dr. Fatma Özkan',
            'Dr. Ali Koç',
            'Dr. Emine Yıldız',
        ];

        foreach ($dentistNames as $index => $name) {
            DB::table('users')->insert([
                'name' => $name,
                'email' => 'dentist' . ($index + 1) . '@dishekimi.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber,
                'role' => 'dentist',
                'locale' => 'tr',
                'dark_mode' => $faker->boolean(30),
                'is_active' => true,
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now(),
            ]);
        }

        // Receptionists
        $receptionistNames = [
            'Elif Kara',
            'Burak Aydın',
            'Selin Güneş',
        ];

        foreach ($receptionistNames as $index => $name) {
            DB::table('users')->insert([
                'name' => $name,
                'email' => 'receptionist' . ($index + 1) . '@dishekimi.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber,
                'role' => 'receptionist',
                'locale' => 'tr',
                'dark_mode' => $faker->boolean(30),
                'is_active' => true,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }

        // Accountant
        DB::table('users')->insert([
            'name' => 'Hasan Bilgi',
            'email' => 'accountant@dishekimi.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => $faker->phoneNumber,
            'role' => 'accountant',
            'locale' => 'tr',
            'dark_mode' => $faker->boolean(30),
            'is_active' => true,
            'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ]);

        // Assistants
        $assistantNames = [
            'Deniz Arslan',
            'Gülşen Tekin',
        ];

        foreach ($assistantNames as $index => $name) {
            DB::table('users')->insert([
                'name' => $name,
                'email' => 'assistant' . ($index + 1) . '@dishekimi.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber,
                'role' => 'assistant',
                'locale' => 'tr',
                'dark_mode' => $faker->boolean(30),
                'is_active' => true,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}