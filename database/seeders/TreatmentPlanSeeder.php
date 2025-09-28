<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TreatmentPlanSeeder extends Seeder
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
        $treatments = DB::table('treatments')->get(['id', 'default_price']);

        if (empty($patients) || empty($dentists) || $treatments->isEmpty()) {
            return;
        }

        $statuses = ['draft', 'active', 'completed', 'cancelled'];
        $toothNumbers = ['11', '12', '13', '14', '15', '16', '17', '18', '21', '22', '23', '24', '25', '26', '27', '28', '31', '32', '33', '34', '35', '36', '37', '38', '41', '42', '43', '44', '45', '46', '47', '48'];

        // Create 100 treatment plans
        for ($i = 0; $i < 100; $i++) {
            $patientId = $faker->randomElement($patients);
            $dentistId = $faker->randomElement($dentists);

            $status = $faker->randomElement($statuses);
            $createdAt = $faker->dateTimeBetween('-1 year', 'now');

            // Create treatment plan
            $planNotes = $this->getTreatmentPlanNotes($faker);

            DB::table('treatment_plans')->insert([
                'patient_id' => $patientId,
                'dentist_id' => $dentistId,
                'total_estimated_cost' => 0, // Will be calculated from items
                'notes' => $planNotes,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
            ]);

            $planId = DB::getPdo()->lastInsertId();

            // Create 2-8 treatment plan items
            $itemCount = $faker->numberBetween(2, 8);
            $totalCost = 0;
            $selectedTreatments = $faker->randomElements($treatments->toArray(), $itemCount);

            foreach ($selectedTreatments as $treatment) {
                $toothNumber = $faker->randomElement($toothNumbers);
                $estimatedPrice = $treatment->default_price * $faker->numberBetween(90, 110) / 100; // ±10% variation

                $itemStatus = $this->getItemStatusFromPlanStatus($status, $faker);

                DB::table('treatment_plan_items')->insert([
                    'treatment_plan_id' => $planId,
                    'treatment_id' => $treatment->id,
                    'appointment_id' => null, // Will be assigned later if needed
                    'tooth_number' => $toothNumber,
                    'estimated_price' => round($estimatedPrice, 2),
                    'status' => $itemStatus,
                    'completed_at' => $itemStatus === 'done' ? $faker->dateTimeBetween($createdAt, 'now') : null,
                    'cancelled_at' => $itemStatus === 'cancelled' ? $faker->dateTimeBetween($createdAt, 'now') : null,
                    'created_at' => $createdAt,
                    'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
                ]);

                $totalCost += $estimatedPrice;
            }

            // Update total cost
            DB::table('treatment_plans')
                ->where('id', $planId)
                ->update(['total_estimated_cost' => round($totalCost, 2)]);
        }
    }

    private function getTreatmentPlanNotes($faker): ?string
    {
        if (!$faker->boolean(70)) {
            return null;
        }

        $notes = [
            'Kapsamlı diş tedavisi planı hazırlandı.',
            'Hasta implant ve protez tedavisini kabul etti.',
            'Ortodontik tedavi planlandı.',
            'Çocuk diş hekimi kontrolü gerekli.',
            'Diş eti tedavisi öncelikli.',
            'Kanal tedavisi planlandı.',
            'Estetik gülüş tasarımı yapıldı.',
            'Acil diş çekimi gerekli.',
            'Diş beyazlatma dahil edildi.',
            'Sigara bırakma önerildi.',
            'Ağız hijyeni eğitimi verilecek.',
            'Periyodik kontroller planlandı.',
            'Hasta tedavi maliyetini öğrendi.',
            'Aile desteği alındı.',
            'Uzun vadeli tedavi planı.'
        ];

        return $faker->randomElement($notes);
    }

    private function getItemStatusFromPlanStatus(string $planStatus, $faker): string
    {
        return match ($planStatus) {
            'draft' => $faker->randomElement(['planned', 'planned', 'planned', 'cancelled']),
            'active' => $faker->randomElement(['planned', 'in_progress', 'done', 'cancelled']),
            'completed' => $faker->randomElement(['done', 'done', 'cancelled']),
            'cancelled' => $faker->randomElement(['planned', 'cancelled', 'cancelled']),
            default => 'planned'
        };
    }
}