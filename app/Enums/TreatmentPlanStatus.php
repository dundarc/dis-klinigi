<?php

namespace App\Enums;

enum TreatmentPlanStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('Taslak'),
            self::ACTIVE => __('Aktif'),
            self::COMPLETED => __('Tamamlandı'),
            self::CANCELLED => __('İptal Edildi'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::ACTIVE => 'blue',
            self::COMPLETED => 'green',
            self::CANCELLED => 'red',
        };
    }
}
