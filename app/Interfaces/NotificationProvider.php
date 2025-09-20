<?php

namespace App\Interfaces;

use App\Models\Patient;

interface NotificationProvider
{
    /**
     * Belirtilen hastaya bir mesaj gönderir.
     *
     * @param Patient $patient Mesajın gönderileceği hasta.
     * @param string $message Gönderilecek metin mesajı.
     * @return bool Gönderimin başarılı olup olmadığını döndürür.
     */
    public function send(Patient $patient, string $message): bool;
}