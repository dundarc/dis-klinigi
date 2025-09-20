<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;
use App\Services\Notifications\Providers\LogSmsProvider; // Dummy provider'ımızı kullanıyoruz

class SendAppointmentReminders extends Command
{
    /**
     * Komutun adı ve imzası.
     * php artisan app:send-reminders olarak çağrılacak.
     */
    protected $signature = 'app:send-reminders';

    /**
     * Komutun açıklaması.
     */
    protected $description = 'Yarınki randevular için hastalara hatırlatma bildirimleri gönderir.';

    /**
     * Komutu çalıştıran ana metod.
     */
    public function handle()
    {
        $this->info('Randevu hatırlatmaları gönderimi başlatılıyor...');

        $tomorrow = Carbon::tomorrow()->toDateString();

        $appointments = Appointment::with('patient')
            ->whereDate('start_at', $tomorrow)
            // Henüz iptal edilmemiş veya tamamlanmamış randevuları seç
            ->whereNotIn('status', ['completed', 'cancelled', 'no_show']) 
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('Yarın için gönderilecek hatırlatma bulunamadı.');
            return 0; // Başarılı ama işlem yok
        }

        // Dummy SMS provider'ımızı oluşturuyoruz.
        // İleride burası App Service Provider üzerinden yönetilecek.
        $smsProvider = new LogSmsProvider();
        $successCount = 0;

        // Her bir randevu için döngü başlat
        foreach ($appointments as $appointment) {
            $patient = $appointment->patient;
            $startTime = $appointment->start_at->format('H:i');

            $message = "Sayın {$patient->first_name} {$patient->last_name}, yarın saat {$startTime}'deki randevunuzu hatırlatırız. Sağlıklı günler dileriz.";

            if ($smsProvider->send($patient, $message)) {
                $successCount++;
                $this->line("-> {$patient->first_name} {$patient->last_name} için hatırlatma gönderildi.");
            } else {
                $this->error("-> {$patient->first_name} {$patient->last_name} için hatırlatma GÖNDERİLEMEDİ!");
            }
        }

        $this->info("Toplam {$successCount} adet hatırlatma başarıyla gönderildi.");
        return 0; // Başarılı
    }
}