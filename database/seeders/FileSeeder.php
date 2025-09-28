<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get existing patients, encounters, and users
        $patients = DB::table('patients')->pluck('id')->toArray();
        $encounters = DB::table('encounters')->pluck('id')->toArray();
        $users = DB::table('users')->pluck('id')->toArray();

        if (empty($patients) || empty($encounters) || empty($users)) {
            return;
        }

        $fileTypes = ['xray', 'document', 'photo', 'other'];
        $mimeTypes = [
            'xray' => ['image/jpeg', 'image/png', 'application/dicom'],
            'document' => ['application/pdf', 'image/jpeg', 'image/png'],
            'photo' => ['image/jpeg', 'image/png'],
            'other' => ['application/pdf', 'image/jpeg', 'image/png', 'application/msword']
        ];

        $fileExtensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
            'application/dicom' => 'dcm',
            'application/msword' => 'doc'
        ];

        // Sample file notes in Turkish
        $fileNotes = [
            'Panoramik röntgen çekildi.',
            'Diş röntgeni çekildi.',
            'Tedavi öncesi fotoğraf.',
            'Tedavi sonrası fotoğraf.',
            'Hasta dosyası eklendi.',
            'Reçete taraması.',
            'Sigorta belgesi.',
            'Hasta rızası.',
            'Önceki tedavi kayıtları.',
            'Referans doktor raporu.',
            'Laboratuvar sonucu.',
            'İlaç prospektüsü.',
            'Hasta kimlik fotokopisi.',
            'Tedavi planı belgesi.',
            'Fatura kopyası.'
        ];

        // Create files for encounters
        foreach ($encounters as $encounterId) {
            // 60% chance of having files
            if ($faker->boolean(60)) {
                $fileCount = $faker->numberBetween(1, 3);

                for ($i = 0; $i < $fileCount; $i++) {
                    $type = $faker->randomElement($fileTypes);
                    $mimeType = $faker->randomElement($mimeTypes[$type]);
                    $extension = $fileExtensions[$mimeType];

                    // Get patient_id from encounter
                    $encounter = DB::table('encounters')->where('id', $encounterId)->first();
                    $patientId = $encounter->patient_id;

                    $originalFilename = $this->generateFileName($type, $faker) . '.' . $extension;
                    $filename = 'file_' . $faker->unique()->numberBetween(100000, 999999) . '.' . $extension;
                    $path = 'patient_files/' . $patientId . '/' . $filename;

                    DB::table('files')->insert([
                        'patient_id' => $patientId,
                        'encounter_id' => $encounterId,
                        'uploader_id' => $faker->randomElement($users),
                        'type' => $type,
                        'filename' => $filename,
                        'original_filename' => $originalFilename,
                        'path' => $path,
                        'mime_type' => $mimeType,
                        'size' => $faker->numberBetween(10000, 5000000), // 10KB to 5MB
                        'notes' => $faker->boolean(50) ? $faker->randomElement($fileNotes) : null,
                        'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Create some additional files not linked to encounters
        for ($i = 0; $i < 100; $i++) {
            $type = $faker->randomElement($fileTypes);
            $mimeType = $faker->randomElement($mimeTypes[$type]);
            $extension = $fileExtensions[$mimeType];
            $patientId = $faker->randomElement($patients);

            $originalFilename = $this->generateFileName($type, $faker) . '.' . $extension;
            $filename = 'file_' . $faker->unique()->numberBetween(100000, 999999) . '.' . $extension;
            $path = 'patient_files/' . $patientId . '/' . $filename;

            DB::table('files')->insert([
                'patient_id' => $patientId,
                'encounter_id' => null,
                'uploader_id' => $faker->randomElement($users),
                'type' => $type,
                'filename' => $filename,
                'original_filename' => $originalFilename,
                'path' => $path,
                'mime_type' => $mimeType,
                'size' => $faker->numberBetween(10000, 5000000),
                'notes' => $faker->boolean(50) ? $faker->randomElement($fileNotes) : null,
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }

    private function generateFileName(string $type, $faker): string
    {
        $prefixes = [
            'xray' => ['röntgen', 'xray', 'panoramik', 'diş_röntgeni', 'çene_röntgeni'],
            'document' => ['belge', 'dosya', 'rapor', 'reçete', 'fatura', 'sözleşme'],
            'photo' => ['fotoğraf', 'resim', 'klinik_foto', 'tedavi_foto', 'öncesi_sonrası'],
            'other' => ['dosya', 'belge', 'kayıt', 'arşiv']
        ];

        $prefix = $faker->randomElement($prefixes[$type]);
        $suffix = $faker->numberBetween(100, 999);

        return $prefix . '_' . $suffix;
    }
}
