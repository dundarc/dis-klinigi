<?php

namespace Database\Seeders;

use App\Models\PatientXray;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatientXRaySeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $users = User::all();

        if ($patients->isEmpty() || $users->isEmpty()) {
            return;
        }

        $xrayNames = [
            'Hasta Panoramik Röntgen',
            'Hasta Çene Röntgeni',
            'Hasta Diş Röntgeni #11',
            'Hasta Diş Röntgeni #12',
            'Hasta Diş Röntgeni #21',
            'Hasta Diş Röntgeni #22',
            'Hasta Diş Röntgeni #31',
            'Hasta Diş Röntgeni #32',
            'Hasta Diş Röntgeni #41',
            'Hasta Diş Röntgeni #42',
            'Hasta Periapikal Röntgen',
            'Hasta Bitewing Röntgen',
            'Hasta CBCT Tarama',
            'Hasta 3D Röntgen',
            'Hasta Kontrol Röntgeni'
        ];

        $notes = [
            'Hasta için ilk muayene',
            'Tedavi öncesi hasta görüntüsü',
            'Tedavi sonrası hasta kontrolü',
            'Kanal tedavisi için hasta',
            'İmplant planlaması hasta',
            'Ortodontik değerlendirme hasta',
            'Çene eklemi sorunu hasta',
            'Diş eti hastalığı kontrolü hasta',
            'Kist şüphesi hasta',
            'Kırık diş değerlendirmesi hasta'
        ];

        $paths = [
            'patient_xray/panoramic-001.jpg',
            'patient_xray/periapical-001.jpg',
            'patient_xray/bitewing-001.jpg',
            'patient_xray/cbct-001.jpg',
            'patient_xray/3d-001.jpg'
        ];

        for ($i = 0; $i < 30; $i++) {
            $patient = $patients->random();
            $uploader = $users->random();

            PatientXray::create([
                'patient_id' => $patient->id,
                'uploader_id' => $uploader->id,
                'name' => collect($xrayNames)->random(),
                'path' => collect($paths)->random(),
                'notes' => collect($notes)->random(),
            ]);
        }
    }
}