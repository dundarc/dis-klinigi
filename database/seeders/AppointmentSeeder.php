<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get existing patients and dentists
        $patients = DB::table('patients')->pluck('id')->toArray();
        $dentists = DB::table('users')->where('role', 'dentist')->pluck('id')->toArray();

        if (empty($patients) || empty($dentists)) {
            return; // Skip if no patients or dentists exist
        }

        $statuses = ['scheduled', 'confirmed', 'checked_in', 'in_service', 'completed', 'cancelled', 'no_show'];
        $statusWeights = [30, 25, 15, 10, 10, 5, 5]; // Weighted distribution

        // Generate 500 appointments
        for ($i = 0; $i < 500; $i++) {
            $patientId = $faker->randomElement($patients);
            $dentistId = $faker->randomElement($dentists);

            // Generate appointment date (past and future)
            $startDate = $faker->dateTimeBetween('-6 months', '+3 months');

            // Working hours: 8:00-18:00, appointments every 30-60 minutes
            $hour = $faker->numberBetween(8, 17);
            $minute = $faker->randomElement([0, 30]);
            $startDate->setTime($hour, $minute, 0);

            $duration = $faker->randomElement([30, 45, 60, 90, 120]); // minutes
            $endDate = clone $startDate;
            $endDate->modify("+{$duration} minutes");

            $status = $faker->randomElement($statuses); // Simplified, could use weighted selection

            // Status-specific logic
            $checkedInAt = null;
            $calledAt = null;

            if (in_array($status, ['checked_in', 'in_service', 'completed'])) {
                $checkedInAt = $faker->dateTimeBetween($startDate, $endDate);
            }

            if ($status === 'in_service') {
                $calledAt = $faker->dateTimeBetween($checkedInAt ?? $startDate, $endDate);
            }

            // Queue number for today appointments
            $queueNumber = null;
            if ($startDate->format('Y-m-d') === date('Y-m-d') && in_array($status, ['scheduled', 'confirmed', 'checked_in', 'in_service'])) {
                $queueNumber = $faker->numberBetween(1, 20);
            }

            // Turkish appointment notes
            $notes = null;
            if ($faker->boolean(40)) {
                $noteOptions = [
                    'İlk muayene',
                    'Kontrol randevusu',
                    'Acil durum',
                    'Tedavi planlaması',
                    'Diş temizliği',
                    'Dolgu yenileme',
                    'Kanal tedavisi devamı',
                    'İmplant kontrolü',
                    'Ortodonti kontrolü',
                    'Hasta geç kaldı',
                    'Randevu iptal edildi',
                    'Hasta gelmedi',
                    'Tedavi tamamlandı'
                ];
                $notes = $faker->randomElement($noteOptions);
            }

            DB::table('appointments')->insert([
                'patient_id' => $patientId,
                'dentist_id' => $dentistId,
                'treatment_plan_id' => null, // Will be set later if treatment plans exist
                'start_at' => $startDate->format('Y-m-d H:i:s'),
                'end_at' => $endDate->format('Y-m-d H:i:s'),
                'status' => $status,
                'room' => $faker->randomElement(['Oda 1', 'Oda 2', 'Oda 3', 'Oda 4', 'Acil']),
                'notes' => $notes,
                'queue_number' => $queueNumber,
                'checked_in_at' => $checkedInAt?->format('Y-m-d H:i:s'),
                'called_at' => $calledAt?->format('Y-m-d H:i:s'),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ]);
        }
    }
}
