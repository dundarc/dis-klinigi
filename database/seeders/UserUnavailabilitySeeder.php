<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserUnavailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get dentists and assistants
        $users = DB::table('users')
            ->whereIn('role', ['dentist', 'assistant'])
            ->pluck('id')
            ->toArray();

        if (empty($users)) {
            return;
        }

        $types = ['vacation', 'sick_leave', 'training', 'personal', 'other'];
        $statuses = ['pending', 'approved', 'rejected'];

        $typeTitles = [
            'vacation' => ['Yıllık İzin', 'Tatil', 'İzin'],
            'sick_leave' => ['Hastalık İzni', 'Raporlu', 'Tedavi İçin'],
            'training' => ['Eğitim', 'Seminer', 'Kurs'],
            'personal' => ['Kişisel İşler', 'Ailevi Nedenler', 'Özel İzin'],
            'other' => ['Diğer', 'İdari İzin', 'Spor Faaliyeti']
        ];

        // Create unavailabilities for users
        foreach ($users as $userId) {
            // Each user gets 1-3 unavailability periods
            $unavailabilityCount = $faker->numberBetween(1, 3);

            for ($i = 0; $i < $unavailabilityCount; $i++) {
                $type = $faker->randomElement($types);
                $title = $faker->randomElement($typeTitles[$type]);

                // Generate dates (past and future)
                $startDate = $faker->dateTimeBetween('-2 months', '+3 months');

                // Duration based on type
                $durationDays = match ($type) {
                    'vacation' => $faker->numberBetween(3, 14),
                    'sick_leave' => $faker->numberBetween(1, 7),
                    'training' => $faker->numberBetween(1, 3),
                    'personal' => $faker->numberBetween(1, 2),
                    'other' => $faker->numberBetween(1, 5),
                };

                $endDate = clone $startDate;
                $endDate->modify("+{$durationDays} days");

                // For same-day unavailabilities, set specific times
                if ($durationDays === 1) {
                    $startTime = $faker->randomElement(['08:00', '09:00', '10:00', '14:00']);
                    $endTime = $faker->randomElement(['12:00', '13:00', '17:00', '18:00']);

                    $startDate->setTime(explode(':', $startTime)[0], explode(':', $startTime)[1]);
                    $endDate->setTime(explode(':', $endTime)[0], explode(':', $endTime)[1]);
                    $isAllDay = false;
                } else {
                    $isAllDay = $faker->boolean(80); // 80% are all day for multi-day
                }

                $status = $faker->randomElement($statuses);
                $isRecurring = $faker->boolean(10); // 10% are recurring

                $recurrencePattern = null;
                if ($isRecurring) {
                    $recurrencePattern = [
                        'frequency' => $faker->randomElement(['weekly', 'monthly']),
                        'interval' => $faker->numberBetween(1, 4),
                        'end_date' => $faker->dateTimeBetween($endDate, '+6 months')->format('Y-m-d'),
                    ];
                }

                $description = $this->getUnavailabilityDescription($type, $faker);
                $adminNotes = ($status === 'rejected') ? $faker->randomElement([
                    'İzin talebi reddedildi.',
                    'Yetersiz personel nedeniyle onaylanmadı.',
                    'Tatil dönemine denk geldiği için ertelendi.'
                ]) : null;

                DB::table('user_unavailabilities')->insert([
                    'user_id' => $userId,
                    'title' => $title,
                    'description' => $description,
                    'type' => $type,
                    'start_datetime' => $startDate->format('Y-m-d H:i:s'),
                    'end_datetime' => $endDate->format('Y-m-d H:i:s'),
                    'is_all_day' => $isAllDay,
                    'is_recurring' => $isRecurring,
                    'recurrence_pattern' => $isRecurring ? json_encode($recurrencePattern) : null,
                    'status' => $status,
                    'admin_notes' => $adminNotes,
                    'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-1 month', 'now'),
                ]);
            }
        }
    }

    private function getUnavailabilityDescription(string $type, $faker): ?string
    {
        if (!$faker->boolean(70)) {
            return null;
        }

        $descriptions = [
            'vacation' => [
                'Aile tatili için izin.',
                'Yıllık izin kullanımı.',
                'Dinlenme amacıyla tatil.',
                'Yurt dışı seyahati.',
            ],
            'sick_leave' => [
                'Grip nedeniyle raporlu.',
                'Diş tedavisi için.',
                'Cerrahi müdahale sonrası iyileşme.',
                'Doktor kontrolü.',
            ],
            'training' => [
                'Mesleki eğitim semineri.',
                'Yeni teknik eğitim.',
                'Sertifika programı.',
                'Kongre katılımı.',
            ],
            'personal' => [
                'Kişisel işler için.',
                'Ailevi nedenler.',
                'Önemli randevu.',
                'Taşınma işlemleri.',
            ],
            'other' => [
                'Spor müsabakası.',
                'Gönüllü çalışma.',
                'Resmi davet.',
                'Aile ziyareti.',
            ],
        ];

        return $faker->randomElement($descriptions[$type]);
    }
}
