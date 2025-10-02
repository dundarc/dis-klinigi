<?php

namespace App\Enums;

enum KvkkAuditAction: string
{
    case SOFT_DELETE = 'soft_delete';
    case HARD_DELETE = 'hard_delete';
    case RESTORE = 'restore';
    case EXPORT = 'export';
    case CREATE_CONSENT = 'create_consent';
    case CANCEL_CONSENT = 'cancel_consent';

    public function label(): string
    {
        return match ($this) {
            self::SOFT_DELETE => 'Yumuşak Silme',
            self::HARD_DELETE => 'Kalıcı Silme',
            self::RESTORE => 'Geri Yükleme',
            self::EXPORT => 'Veri Dışa Aktarma',
            self::CREATE_CONSENT => 'Onam Oluşturma',
            self::CANCEL_CONSENT => 'Onam İptali',
        };
    }
}
