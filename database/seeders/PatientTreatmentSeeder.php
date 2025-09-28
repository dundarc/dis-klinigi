<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PatientTreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get existing encounters and treatments
        $encounters = DB::table('encounters')->where('status', 'done')->get(['id', 'patient_id', 'dentist_id', 'started_at', 'ended_at']);
        $treatments = DB::table('treatments')->get(['id', 'default_price', 'default_vat']);

        if ($encounters->isEmpty() || $treatments->isEmpty()) {
            return;
        }

        $toothNumbers = ['11', '12', '13', '14', '15', '16', '17', '18', '21', '22', '23', '24', '25', '26', '27', '28', '31', '32', '33', '34', '35', '36', '37', '38', '41', '42', '43', '44', '45', '46', '47', '48'];

        // Create treatments for completed encounters
        foreach ($encounters as $encounter) {
            // Each encounter has 1-4 treatments
            $treatmentCount = $faker->numberBetween(1, 4);

            for ($i = 0; $i < $treatmentCount; $i++) {
                $treatment = $faker->randomElement($treatments);

                // Calculate performed_at between started_at and ended_at
                $performedAt = null;
                if ($encounter->started_at && $encounter->ended_at) {
                    $performedAt = $faker->dateTimeBetween($encounter->started_at, $encounter->ended_at);
                } elseif ($encounter->started_at) {
                    $performedAt = $faker->dateTimeBetween($encounter->started_at, $encounter->started_at . ' +2 hours');
                }

                // Price variation (±20%)
                $priceVariation = $faker->numberBetween(-20, 20);
                $unitPrice = $treatment->default_price * (1 + $priceVariation / 100);

                // Discount (0-15%)
                $discount = $faker->boolean(30) ? $faker->numberBetween(0, 15) : 0;

                DB::table('patient_treatments')->insert([
                    'patient_id' => $encounter->patient_id,
                    'encounter_id' => $encounter->id,
                    'dentist_id' => $encounter->dentist_id,
                    'treatment_id' => $treatment->id,
                    'tooth_number' => $faker->randomElement($toothNumbers),
                    'status' => 'done',
                    'unit_price' => round($unitPrice, 2),
                    'vat' => $treatment->default_vat,
                    'discount' => $discount,
                    'performed_at' => $performedAt?->format('Y-m-d H:i:s'),
                    'notes' => $this->getTreatmentNotes($faker),
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-3 months', 'now'),
                ]);
            }
        }
    }

    private function getTreatmentNotes($faker): ?string
    {
        if (!$faker->boolean(50)) {
            return null;
        }

        $notes = [
            'Tedavi başarıyla tamamlandı.',
            'Hasta memnun kaldı.',
            'Bir sonraki seans için randevu verildi.',
            'Hasta ağrı hissetti.',
            'Lokal anestezi uygulandı.',
            'Geçici dolgu yapıldı.',
            'Hasta tedaviyi tolere etti.',
            'Kontrol randevusu gerekli.',
            'Ağrı kesici önerildi.',
            'Ağız bakımı talimatları verildi.',
            'Hasta sorularını sordu.',
            'Tedavi süresi uzadı.',
            'Hasta işbirliği yaptı.',
            'Profesyonel temizlik yapıldı.',
            'Röntgen çekildi.',
            'Ölçü alındı.',
            'Geçici protez uygulandı.',
            'Hasta eğitim verildi.',
            'İyileşme süreci açıklandı.',
            'Kontrol için çağrılacak.'
        ];

        return $faker->randomElement($notes);
    }
}
