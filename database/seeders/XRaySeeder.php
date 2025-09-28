<?php

namespace Database\Seeders;

use App\Models\XRay;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class XRaySeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $users = User::all();

        if ($patients->isEmpty() || $users->isEmpty()) {
            return;
        }

        $xrayNames = [
            'Panoramik Röntgen',
            'Çene Röntgeni',
            'Diş Röntgeni #11',
            'Diş Röntgeni #12',
            'Diş Röntgeni #21',
            'Diş Röntgeni #22',
            'Diş Röntgeni #31',
            'Diş Röntgeni #32',
            'Diş Röntgeni #41',
            'Diş Röntgeni #42',
            'Periapikal Röntgen',
            'Bitewing Röntgen',
            'CBCT Tarama',
            '3D Röntgen',
            'Kontrol Röntgeni'
        ];

        $notes = [
            'İlk muayene için çekildi',
            'Tedavi öncesi görüntü',
            'Tedavi sonrası kontrol',
            'Kanal tedavisi için',
            'İmplant planlaması',
            'Ortodontik değerlendirme',
            'Çene eklemi sorunu',
            'Diş eti hastalığı kontrolü',
            'Kist şüphesi',
            'Kırık diş değerlendirmesi'
        ];

        $paths = [
            'xray/panoramic-001.jpg',
            'xray/periapical-001.jpg',
            'xray/bitewing-001.jpg',
            'xray/cbct-001.jpg',
            'xray/3d-001.jpg'
        ];

        for ($i = 0; $i < 50; $i++) {
            $patient = $patients->random();
            $uploader = $users->random();

            XRay::create([
                'patient_id' => $patient->id,
                'uploader_id' => $uploader->id,
                'name' => collect($xrayNames)->random(),
                'path' => collect($paths)->random(),
                'notes' => collect($notes)->random(),
            ]);
        }
    }
}