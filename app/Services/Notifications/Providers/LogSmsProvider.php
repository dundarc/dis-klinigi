<?php

namespace App\Services\Notifications\Providers;

use App\Interfaces\NotificationProvider;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;

class LogSmsProvider implements NotificationProvider
{
    public function send(Patient $patient, string $message): bool
    {
        // Gerçek bir SMS API'si yerine, bilgileri log dosyasına yazıyoruz.
        // Bu, geliştirme aşamasında test için mükemmeldir.
        Log::info("SMS GÖNDERİLDİ:");
        Log::info("  Alıcı Tel: " . $patient->phone_primary);
        Log::info("  Hasta: " . $patient->first_name . ' ' . $patient->last_name);
        Log::info("  Mesaj: " . $message);

        return true; // Şimdilik hep başarılı varsayalım.
    }
}