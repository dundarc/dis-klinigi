<?php

namespace App\Enums;

enum ConsentStatus: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => __('patient.kvkk.active'),
            self::PENDING => __('patient.kvkk.pending'),
            self::CANCELED => __('patient.kvkk.canceled'),
        };
    }
}
