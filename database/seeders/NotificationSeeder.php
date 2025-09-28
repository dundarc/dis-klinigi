<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get existing users
        $users = DB::table('users')->pluck('id')->toArray();

        if (empty($users)) {
            return;
        }

        $notificationTypes = [
            'appointment_reminder' => [
                'title' => 'Randevu Hatırlatması',
                'messages' => [
                    'Yarın saat {time} randevunuz var.',
                    'Bugün saat {time} randevunuz bulunuyor.',
                    '{date} tarihinde randevunuz planlanmıştır.',
                ]
            ],
            'appointment_cancelled' => [
                'title' => 'Randevu İptali',
                'messages' => [
                    '{date} tarihindeki randevunuz iptal edilmiştir.',
                    'Hasta randevuyu iptal etmiştir.',
                    'Acil durum nedeniyle randevu ertelenmiştir.',
                ]
            ],
            'payment_reminder' => [
                'title' => 'Ödeme Hatırlatması',
                'messages' => [
                    '{amount} TL tutarındaki faturanız ödenmemiştir.',
                    'Son ödeme tarihi yaklaşmaktadır.',
                    'Ödenmemiş fatura bulunmaktadır.',
                ]
            ],
            'treatment_completed' => [
                'title' => 'Tedavi Tamamlandı',
                'messages' => [
                    '{patient} hastasının tedavisi tamamlandı.',
                    'Kanal tedavisi başarıyla sonuçlandı.',
                    'İmplant uygulaması gerçekleştirildi.',
                ]
            ],
            'stock_alert' => [
                'title' => 'Stok Uyarısı',
                'messages' => [
                    '{item} stoğu kritik seviyede.',
                    'Yeni malzeme siparişi gerekli.',
                    'Stok seviyesi minimum altına düştü.',
                ]
            ],
            'system_update' => [
                'title' => 'Sistem Güncellemesi',
                'messages' => [
                    'Yeni özellikler eklendi.',
                    'Sistem bakımı tamamlandı.',
                    'Güvenlik güncellemesi yapıldı.',
                ]
            ],
        ];

        // Create 200 notifications
        for ($i = 0; $i < 200; $i++) {
            $userId = $faker->randomElement($users);
            $type = $faker->randomElement(array_keys($notificationTypes));
            $typeData = $notificationTypes[$type];

            $message = $faker->randomElement($typeData['messages']);

            // Replace placeholders
            $message = str_replace('{time}', $faker->time('H:i'), $message);
            $message = str_replace('{date}', $faker->date('d.m.Y'), $message);
            $message = str_replace('{amount}', $faker->numberBetween(500, 5000), $message);
            $message = str_replace('{patient}', $faker->firstName . ' ' . $faker->lastName, $message);
            $message = str_replace('{item}', $faker->randomElement(['Latex Eldiven', 'Kompozit Dolgu', 'Anestezi İğnesi']), $message);

            $isRead = $faker->boolean(70); // 70% read
            $isCompleted = $faker->boolean(50); // 50% completed

            DB::table('notifications')->insert([
                'user_id' => $userId,
                'type' => $type,
                'title' => $typeData['title'],
                'message' => $message,
                'data' => json_encode([
                    'priority' => $faker->randomElement(['low', 'medium', 'high']),
                    'category' => $type,
                    'reference_id' => $faker->numberBetween(1000, 9999),
                ]),
                'is_read' => $isRead,
                'is_completed' => $isCompleted,
                'read_at' => $isRead ? $faker->dateTimeBetween('-30 days', 'now') : null,
                'completed_at' => $isCompleted ? $faker->dateTimeBetween('-30 days', 'now') : null,
                'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
                'updated_at' => $faker->dateTimeBetween('-30 days', 'now'),
            ]);
        }
    }
}