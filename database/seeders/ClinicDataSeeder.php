<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Enums\UserRole;

class ClinicDataSeeder extends Seeder
{
    public function run(): void
    {
        // 30 hasta oluştur
        $patients = Patient::factory()->count(30)->create();

        // Sadece hekim rolündeki kullanıcıları al
        $dentists = User::where('role', UserRole::DENTIST)->get();

        if ($dentists->isEmpty()) {
            $this->command->warn('Hiç hekim bulunamadı, randevu ve acil durum oluşturma atlanıyor.');
            return;
        }

        // 40 randevu oluştur
        Appointment::factory()->count(40)->make()->each(function ($appointment) use ($patients, $dentists) {
            $appointment->patient_id = $patients->random()->id;
            $appointment->dentist_id = $dentists->random()->id;
            $appointment->save();
        });
        
        // 6 acil vaka oluştur
        Encounter::factory()->count(6)->emergency()->make()->each(function ($encounter) use ($patients) {
            $encounter->patient_id = $patients->random()->id;
            $encounter->save();
        });
        
        // 4 walk-in vaka oluştur
        Encounter::factory()->count(4)->walkIn()->make()->each(function ($encounter) use ($patients) {
            $encounter->patient_id = $patients->random()->id;
            $encounter->save();
        });
    }
}