<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WorkingHour;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Resepsiyonist
        User::factory()->receptionist()->create([
            'name' => 'Ayşe Yılmaz',
            'email' => 'reception@example.com',
        ]);

        // Asistan
        User::factory()->assistant()->create([
            'name' => 'Fatma Öztürk',
            'email' => 'assistant@example.com',
        ]);

        // Hekim 1
        $dentist1 = User::factory()->dentist()->create([
            'name' => 'Dr. Ahmet Çelik',
            'email' => 'ahmet.celik@example.com',
        ]);
        // Hekim 1 Çalışma Saatleri (Hafta içi 09:00-17:00)
        for ($i = 1; $i <= 5; $i++) { // Pzt-Cuma
            WorkingHour::factory()->create([
                'user_id' => $dentist1->id,
                'weekday' => $i,
                'start_time' => '09:00',
                'end_time' => '17:00',
            ]);
        }
        
        // Hekim 2
        $dentist2 = User::factory()->dentist()->create([
            'name' => 'Dr. Zeynep Kaya',
            'email' => 'zeynep.kaya@example.com',
        ]);
         // Hekim 2 Çalışma Saatleri (Pzt, Çrş, Cuma 10:00-18:30)
        foreach ([1, 3, 5] as $day) {
            WorkingHour::factory()->create([
                'user_id' => $dentist2->id,
                'weekday' => $day,
                'start_time' => '10:00',
                'end_time' => '18:30',
            ]);
        }
        
        // Hekim 3
        $dentist3 = User::factory()->dentist()->create([
            'name' => 'Dr. Mustafa Demir',
            'email' => 'mustafa.demir@example.com',
        ]);
        // Hekim 3 Çalışma Saatleri (Sal, Per 08:00-16:00, Cmt 09:00-13:00)
        foreach ([2, 4] as $day) {
            WorkingHour::factory()->create([ 'user_id' => $dentist3->id, 'weekday' => $day, 'start_time' => '08:00', 'end_time' => '16:00']);
        }
        WorkingHour::factory()->create([ 'user_id' => $dentist3->id, 'weekday' => 6, 'start_time' => '09:00', 'end_time' => '13:00']);
    }
}