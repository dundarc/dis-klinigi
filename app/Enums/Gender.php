<?php

namespace App\Enums;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';

    /**
     * Get the human-readable label for the gender.
     */
    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Erkek',
            self::FEMALE => 'Kadın',
            self::OTHER => 'Diğer',
        };
    }
}