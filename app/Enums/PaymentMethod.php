<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case CARD = 'card';
    case TRANSFER = 'transfer';
    case INSURANCE = 'insurance';

    public function label(): string
    {
        return match($this) {
            self::CASH => 'Nakit',
            self::CARD => 'Kart',
            self::TRANSFER => 'Havale',
            self::INSURANCE => 'Sigorta',
        };
    }
}