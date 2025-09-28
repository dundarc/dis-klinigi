<?php

namespace App\Enums;

enum EncounterStatus: string
{
    case WAITING = 'waiting';
    case IN_SERVICE = 'in_service';
    case DONE = 'done';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::WAITING => 'Bekliyor',
            self::IN_SERVICE => 'Hizmet Veriliyor',
            self::DONE => 'Tamamlandı',
            self::CANCELLED => 'İptal Edildi',
        };
    }
}