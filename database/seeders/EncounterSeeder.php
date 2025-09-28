<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EncounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get existing appointments and users
        $appointments = DB::table('appointments')->get(['id', 'patient_id', 'dentist_id', 'start_at', 'status']);
        $patients = DB::table('patients')->pluck('id')->toArray();
        $dentists = DB::table('users')->where('role', 'dentist')->pluck('id')->toArray();

        if (empty($patients) || empty($dentists)) {
            return;
        }

        $types = ['scheduled', 'emergency', 'walk_in'];
        $triageLevels = ['red', 'yellow', 'green'];
        $statuses = ['waiting', 'in_service', 'done', 'cancelled'];

        // Create encounters for appointments
        foreach ($appointments as $appointment) {
            // 80% of appointments have encounters
            if ($faker->boolean(80)) {
                $arrivedAt = $faker->dateTimeBetween(
                    $appointment->start_at . ' -30 minutes',
                    $appointment->start_at . ' +30 minutes'
                );

                $status = $this->getEncounterStatusFromAppointment($appointment->status);
                $type = 'scheduled'; // Since it's linked to appointment

                $startedAt = null;
                $endedAt = null;

                if (in_array($status, ['in_service', 'done'])) {
                    $startedAt = $faker->dateTimeBetween($arrivedAt, $arrivedAt->format('Y-m-d H:i:s') . ' +2 hours');
                }

                if ($status === 'done') {
                    $endedAt = $faker->dateTimeBetween($startedAt, $startedAt->format('Y-m-d H:i:s') . ' +3 hours');
                }

                DB::table('encounters')->insert([
                    'patient_id' => $appointment->patient_id,
                    'appointment_id' => $appointment->id,
                    'dentist_id' => $appointment->dentist_id,
                    'type' => $type,
                    'triage_level' => null, // Scheduled appointments don't have triage
                    'arrived_at' => $arrivedAt->format('Y-m-d H:i:s'),
                    'started_at' => $startedAt?->format('Y-m-d H:i:s'),
                    'ended_at' => $endedAt?->format('Y-m-d H:i:s'),
                    'status' => $status,
                    'notes' => $this->getEncounterNotes($faker),
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-3 months', 'now'),
                ]);
            }
        }

        // Create additional walk-in and emergency encounters (not linked to appointments)
        for ($i = 0; $i < 150; $i++) {
            $patientId = $faker->randomElement($patients);
            $dentistId = $faker->randomElement($dentists);
            $type = $faker->randomElement($types);

            $arrivedAt = $faker->dateTimeBetween('-6 months', 'now');

            $triageLevel = null;
            if (in_array($type, ['emergency', 'walk_in'])) {
                $triageLevel = $faker->randomElement($triageLevels);
            }

            $status = $faker->randomElement($statuses);

            $startedAt = null;
            $endedAt = null;

            if (in_array($status, ['in_service', 'done'])) {
                $startedAt = $faker->dateTimeBetween($arrivedAt, $arrivedAt->format('Y-m-d H:i:s') . ' +4 hours');
            }

            if ($status === 'done') {
                $endedAt = $faker->dateTimeBetween($startedAt, $startedAt->format('Y-m-d H:i:s') . ' +3 hours');
            }

            DB::table('encounters')->insert([
                'patient_id' => $patientId,
                'appointment_id' => null,
                'dentist_id' => $dentistId,
                'type' => $type,
                'triage_level' => $triageLevel,
                'arrived_at' => $arrivedAt->format('Y-m-d H:i:s'),
                'started_at' => $startedAt?->format('Y-m-d H:i:s'),
                'ended_at' => $endedAt?->format('Y-m-d H:i:s'),
                'status' => $status,
                'notes' => $this->getEncounterNotes($faker),
                'created_at' => $arrivedAt,
                'updated_at' => $faker->dateTimeBetween($arrivedAt, 'now'),
            ]);
        }
    }

    private function getEncounterStatusFromAppointment(string $appointmentStatus): string
    {
        return match ($appointmentStatus) {
            'scheduled', 'confirmed' => 'waiting',
            'checked_in' => 'waiting',
            'in_service' => 'in_service',
            'completed' => 'done',
            'cancelled' => 'cancelled',
            'no_show' => 'cancelled',
            default => 'waiting'
        };
    }

    private function getEncounterNotes($faker): ?string
    {
        if (!$faker->boolean(60)) {
            return null;
        }

        $notes = [
            'Hasta düzenli kontrole geldi.',
            'Acil diş ağrısı şikayeti.',
            'Diş eti kanaması mevcut.',
            'Önceki tedavi kontrolü yapıldı.',
            'Yeni hasta muayenesi.',
            'Kanal tedavisi planlandı.',
            'İmplant kontrolü yapıldı.',
            'Diş çekimi gerçekleştirildi.',
            'Dolgu yenilemesi yapıldı.',
            'Hasta tedaviyi kabul etti.',
            'Hasta tedaviyi erteledi.',
            'Ağrı kesici reçete edildi.',
            'Antibiyotik reçete edildi.',
            'Bir sonraki randevu verildi.',
            'Hasta memnun kaldı.',
            'Tedavi başarıyla tamamlandı.'
        ];

        return $faker->randomElement($notes);
    }
}
