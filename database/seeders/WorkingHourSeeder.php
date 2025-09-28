<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class WorkingHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get dentists and assistants (users who work with patients)
        $users = DB::table('users')
            ->whereIn('role', ['dentist', 'assistant'])
            ->pluck('id')
            ->toArray();

        if (empty($users)) {
            return;
        }

        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        // Standard clinic hours
        $standardHours = [
            'monday' => ['start' => '08:00', 'end' => '18:00', 'working' => true],
            'tuesday' => ['start' => '08:00', 'end' => '18:00', 'working' => true],
            'wednesday' => ['start' => '08:00', 'end' => '18:00', 'working' => true],
            'thursday' => ['start' => '08:00', 'end' => '18:00', 'working' => true],
            'friday' => ['start' => '08:00', 'end' => '17:00', 'working' => true],
            'saturday' => ['start' => '09:00', 'end' => '14:00', 'working' => true],
            'sunday' => ['start' => '00:00', 'end' => '00:00', 'working' => false],
        ];

        foreach ($users as $userId) {
            foreach ($daysOfWeek as $day) {
                $hours = $standardHours[$day];

                // Some variation for individual schedules
                if ($hours['working']) {
                    // 10% chance of different hours
                    if ($faker->boolean(10)) {
                        $startHour = $faker->numberBetween(7, 9);
                        $endHour = $faker->numberBetween(17, 19);
                        $hours['start'] = sprintf('%02d:00', $startHour);
                        $hours['end'] = sprintf('%02d:00', $endHour);
                    }

                    // 5% chance of day off
                    if ($faker->boolean(5)) {
                        $hours['working'] = false;
                    }
                }

                $notes = null;
                if (!$hours['working']) {
                    $notes = $faker->randomElement([
                        'İzinli',
                        'Tatil',
                        'Eğitim',
                        'Hasta',
                        'Kişisel nedenler',
                        'Ailevi nedenler'
                    ]);
                }

                DB::table('working_hours')->insert([
                    'user_id' => $userId,
                    'day_of_week' => $day,
                    'start_time' => $hours['start'],
                    'end_time' => $hours['end'],
                    'is_working_day' => $hours['working'],
                    'notes' => $notes,
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
