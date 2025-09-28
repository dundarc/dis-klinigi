<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Generate 200 patients
        for ($i = 0; $i < 200; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            // Generate a valid-looking Turkish TC Kimlik number
            $tcKimlik = $this->generateValidTcKimlik();

            // Turkish cities and districts
            $cities = [
                'İstanbul', 'Ankara', 'İzmir', 'Bursa', 'Antalya', 'Konya', 'Adana', 'Gaziantep',
                'Şanlıurfa', 'Kocaeli', 'Kayseri', 'Samsun', 'Denizli', 'Eskişehir', 'Sakarya'
            ];
            $districts = [
                'Kadıköy', 'Beşiktaş', 'Üsküdar', 'Şişli', 'Çankaya', 'Keçiören', 'Konak', 'Bornova',
                'Nilüfer', 'Osmangazi', 'Muratpaşa', 'Konyaaltı', 'Selçuklu', 'Meram', 'Seyhan'
            ];

            $city = $faker->randomElement($cities);
            $district = $faker->randomElement($districts);

            // Turkish tax offices
            $taxOffices = [
                'Kadıköy Vergi Dairesi',
                'Beşiktaş Vergi Dairesi',
                'Çankaya Vergi Dairesi',
                'Konak Vergi Dairesi',
                'Nilüfer Vergi Dairesi',
                'Muratpaşa Vergi Dairesi',
                'Selçuklu Vergi Dairesi',
                'Seyhan Vergi Dairesi'
            ];

            // Sample medications and notes in Turkish
            $medications = [
                'Aspirin 100mg günde 1 kez',
                'Parol 500mg ağrı olduğunda',
                'Amoksisilin 500mg günde 3 kez',
                'İbuprofen 400mg ihtiyaç halinde',
                'Omeprazol 20mg sabahları',
                'Metformin 500mg günde 2 kez',
                'Losartan 50mg günde 1 kez',
                'Atorvastatin 10mg akşamları',
                'Hipertansiyon ilacı',
                'Şeker hastalığı ilacı',
                'Tansiyon düşürücü',
                'Kolesterol ilacı',
                'Mide koruyucu',
                'Ağrı kesici',
                'Antibiyotik'
            ];

            $notes = [
                'Düzenli kontrolleri var.',
                'Diş eti problemi yaşıyor.',
                'Sigara kullanıyor.',
                'Çok fazla kahve tüketiyor.',
                'Diş sıkma alışkanlığı var.',
                'Gebelik döneminde dikkatli olunmalı.',
                'Kalp ameliyatı geçirmiş.',
                'Alerjik reaksiyon gösterebilir.',
                'Önceki tedavilerden memnun.',
                'Randevu saatine dikkat ediyor.',
                'Özel sigorta var.',
                'Ödeme konusunda hassas.',
                'Aile diş hekimi arıyor.',
                'Çocukları da getirebilir.',
                'Uzun süredir hastamız.'
            ];

            DB::table('patients')->insert([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'national_id' => $tcKimlik,
                'birth_date' => $faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'phone_primary' => $this->generateTurkishPhone(),
                'phone_secondary' => $faker->boolean(30) ? $this->generateTurkishPhone() : null,
                'email' => $faker->boolean(70) ? strtolower($firstName . '.' . $lastName . $i . '@' . $faker->randomElement(['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com'])) : null,
                'address_text' => $district . ' Mahallesi, ' . $faker->streetName . ' Sokak, No: ' . $faker->numberBetween(1, 200) . ', ' . $city,
                'tax_office' => $faker->randomElement($taxOffices),
                'consent_kvkk_at' => $faker->boolean(85) ? $faker->dateTimeBetween('-2 years', 'now') : null,
                'notes' => $faker->boolean(60) ? $faker->randomElement($notes) : null,
                'emergency_contact_person' => $faker->boolean(40) ? $faker->name : null,
                'emergency_contact_phone' => $faker->boolean(40) ? $this->generateTurkishPhone() : null,
                'medications_used' => $faker->boolean(50) ? $faker->randomElement($medications) : null,
                'has_private_insurance' => $faker->boolean(30),
                'created_at' => $faker->dateTimeBetween('-3 years', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }

    /**
     * Generate a valid-looking Turkish TC Kimlik number
     */
    private function generateValidTcKimlik(): string
    {
        do {
            $digits = [];
            for ($i = 0; $i < 9; $i++) {
                $digits[] = rand(0, 9);
            }

            // Calculate checksum digits
            $oddSum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8];
            $evenSum = $digits[1] + $digits[3] + $digits[5] + $digits[7];

            $digit10 = ((($oddSum * 7) - $evenSum) % 10 + 10) % 10;
            $digit11 = (($oddSum + $evenSum + $digit10) % 10 + 10) % 10;

            $tcKimlik = implode('', $digits) . $digit10 . $digit11;

            // Check if it already exists
            $exists = DB::table('patients')->where('national_id', $tcKimlik)->exists();

        } while ($exists);

        return $tcKimlik;
    }

    /**
     * Generate a Turkish phone number
     */
    private function generateTurkishPhone(): string
    {
        $faker = Faker::create('tr_TR');
        $operators = ['501', '502', '503', '504', '505', '506', '507', '508', '509', '510', '511', '512', '513', '514', '515', '516', '517', '518', '519', '520', '521', '522', '523', '524', '525', '526', '527', '528', '529', '530', '531', '532', '533', '534', '535', '536', '537', '538', '539', '540', '541', '542', '543', '544', '545', '546', '547', '548', '549', '550', '551', '552', '553', '554', '555'];
        $operator = $faker->randomElement($operators);
        $number = sprintf('+90 %s %s %s %s', $operator, rand(100, 999), rand(10, 99), rand(10, 99));
        return $number;
    }
}
