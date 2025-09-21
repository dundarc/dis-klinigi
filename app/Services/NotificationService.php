<?php
namespace App\Services;

use App\Models\User;
use App\Models\Notification;

class NotificationService
{
    /**
     * Belirli bir kullanıcı için veritabanına bildirim kaydı oluşturur.
     *
     * @param User $user Bildirimi alacak kullanıcı.
     * @param string $title Bildirimin başlığı.
     * @param string $body Bildirimin içeriği.
     * @param string|null $linkUrl Bildirime tıklandığında gidilecek URL.
     * @return Notification Oluşturulan bildirim modeli.
     */
    public function createNotification(User $user, string $title, string $body, ?string $linkUrl = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
            'link_url' => $linkUrl,
        ]);
    }
}