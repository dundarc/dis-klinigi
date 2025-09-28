<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get encounters that might need prescriptions
        $encounters = DB::table('encounters')
            ->where('status', 'done')
            ->whereRaw('TIMESTAMPDIFF(HOUR, arrived_at, ended_at) > 0')
            ->get(['id', 'patient_id', 'dentist_id']);

        if ($encounters->isEmpty()) {
            return;
        }

        // Sample prescription texts in Turkish
        $prescriptionTexts = [
            "Parol 500mg tablet\nSig: Ağrı olduğunda 1 tablet\nGünlük maksimum 4 tablet\n7 gün kullanınız",
            "Amoksisilin 500mg kapsül\nSig: Günde 3 kez 1 kapsül\nYemeklerden sonra\n7 gün kullanınız",
            "İbuprofen 400mg tablet\nSig: Günde 3 kez 1 tablet\nYemeklerden sonra\nAğrı kesici olarak",
            "Metronidazol 400mg tablet\nSig: Günde 2 kez 1 tablet\n7 gün kullanınız\nAntibiyotik",
            "Klorheksidin %0.2 gargara\nSig: Günde 2 kez 1 dakika gargara\nDiş eti iltihabı için",
            "Deklofenak 50mg tablet\nSig: Günde 2 kez 1 tablet\nİltihap giderici",
            "Parasetamol 500mg tablet\nSig: Ağrı olduğunda 1 tablet\nGünlük maksimum 4 tablet",
            "Deksametazon 0.1% krem\nSig: Günde 2 kez diş etine sür\nDiş eti iltihabı için",
            "Tetrasiklin 250mg kapsül\nSig: Günde 4 kez 1 kapsül\n10 gün kullanınız",
            "Flukonazol 150mg kapsül\nSig: Tek doz 1 kapsül\nMantar enfeksiyonu için",
            "Asetilsalisilik asit 100mg tablet\nSig: Günde 1 kez 1 tablet\nKalp koruması için",
            "Kalsiyum + D3 vitamini\nSig: Günde 1 kez 1 tablet\nKemik sağlığı için",
            "Çinko sülfat 220mg\nSig: Günde 1 kez 1 tablet\nİmmunite desteği",
            "B kompleks vitamini\nSig: Günde 1 kez 1 tablet\nSinir sistemi desteği",
            "Omega 3 balık yağı\nSig: Günde 2 kez 1 kapsül\nKalp-damar sağlığı"
        ];

        // Create prescriptions for some encounters
        foreach ($encounters as $encounter) {
            // 40% chance of having a prescription
            if ($faker->boolean(40)) {
                DB::table('prescriptions')->insert([
                    'patient_id' => $encounter->patient_id,
                    'dentist_id' => $encounter->dentist_id,
                    'encounter_id' => $encounter->id,
                    'text' => $faker->randomElement($prescriptionTexts),
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create some additional prescriptions not linked to encounters
        $patients = DB::table('patients')->pluck('id')->toArray();
        $dentists = DB::table('users')->where('role', 'dentist')->pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            DB::table('prescriptions')->insert([
                'patient_id' => $faker->randomElement($patients),
                'dentist_id' => $faker->randomElement($dentists),
                'encounter_id' => null,
                'text' => $faker->randomElement($prescriptionTexts),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
