<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case DENTIST = 'dentist';
    case ASSISTANT = 'assistant';
    case RECEPTIONIST = 'receptionist';
    case ACCOUNTANT = 'accountant';

    public function displayName(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::DENTIST => 'Diş Hekimi',
            self::ASSISTANT => 'Asistan',
            self::RECEPTIONIST => 'Resepsiyonist',
            self::ACCOUNTANT => 'Muhasebeci',
        };
    }
}
