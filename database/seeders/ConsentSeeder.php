<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ConsentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get existing patients
        $patients = DB::table('patients')->pluck('id')->toArray();

        if (empty($patients)) {
            return;
        }

        $consentTypes = [
            'kvkk' => [
                'title' => 'KVKK Aydınlatma Metni ve Açık Rıza Beyanı',
                'content' => '6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında kişisel verilerimin işlenmesi, aktarılması ve saklanması ile ilgili olarak bilgilendirildim ve açık rızamı veriyorum. Kişisel verilerimin işlenme amaçları, hukuki sebebi, toplanma yöntemi, aktarılabileceği kişiler ve haklarım konusunda detaylı bilgi aldım.'
            ],
            'treatment' => [
                'title' => 'Tedavi Onam Formu',
                'content' => 'Önerilen tedavi yöntemleri, olası riskler, faydalar ve alternatif tedavi seçenekleri hakkında detaylı bilgi aldım. Tedavi sürecinde oluşabilecek komplikasyonlar ve yan etkiler konusunda bilgilendirildim. Tedaviyi kabul ediyorum ve uygulanmasını onaylıyorum.'
            ],
            'photography' => [
                'title' => 'Fotoğraf ve Video Çekimi İzni',
                'content' => 'Tedavi öncesi, tedavi süreci ve tedavi sonrası çekilecek fotoğrafların ve videoların eğitim, tanıtım ve bilimsel amaçlı kullanımını kabul ediyorum. Kişisel bilgilerimin gizli tutulacağını ve fotoğrafların anonim olarak kullanılacağını onaylıyorum.'
            ],
            'emergency' => [
                'title' => 'Acil Durum Yetkilendirme Formu',
                'content' => 'Acil durumlarda müdahale yetkisi veriyorum. Kanama, enfeksiyon, alerjik reaksiyon gibi durumlarda gerekli müdahalelerin yapılmasını ve ilaç uygulanmasını kabul ediyorum. Acil durumda ulaşılacak kişiler ve iletişim bilgileri sağlandı.'
            ],
            'insurance' => [
                'title' => 'Sigorta Bilgilendirme ve Onay Formu',
                'content' => 'Özel sigortamın kapsamı, limitleri ve tedavi masraflarının karşılanma koşulları hakkında bilgilendirildim. Sigorta şirketine gerekli bilgilerin aktarılmasını ve tedavi masraflarının sigorta kapsamından karşılanmasını kabul ediyorum.'
            ],
            'payment' => [
                'title' => 'Ödeme Koşulları ve Sözleşmesi',
                'content' => 'Tedavi ücretleri, ödeme koşulları ve taksit seçenekleri hakkında bilgilendirildim. Ödemelerin zamanında yapılacağını, gecikme halinde uygulanacak faiz ve masrafları kabul ediyorum. Fatura ve dekont bilgilerinin saklanacağını onaylıyorum.'
            ],
        ];

        // Create consents for patients
        foreach ($patients as $patientId) {
            // Each patient gets 2-4 different consents
            $selectedTypes = $faker->randomElements(array_keys($consentTypes), $faker->numberBetween(2, 4));

            foreach ($selectedTypes as $type) {
                $consentData = $consentTypes[$type];

                // 85% acceptance rate
                $isAccepted = $faker->boolean(85);
                $acceptedAt = $isAccepted ? $faker->dateTimeBetween('-1 year', 'now') : null;

                DB::table('consents')->insert([
                    'patient_id' => $patientId,
                    'type' => $type,
                    'title' => $consentData['title'],
                    'content' => $consentData['content'],
                    'is_accepted' => $isAccepted,
                    'accepted_at' => $acceptedAt,
                    'ip_address' => $isAccepted ? $faker->ipv4 : null,
                    'user_agent' => $isAccepted ? $faker->userAgent : null,
                    'metadata' => json_encode([
                        'version' => '1.0',
                        'language' => 'tr',
                        'signed_by' => $faker->name,
                        'witness' => $faker->boolean(30) ? $faker->name : null,
                        'location' => $faker->randomElement(['Klinik', 'Online', 'Telefon']),
                    ]),
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
                ]);
            }
        }
    }
}
