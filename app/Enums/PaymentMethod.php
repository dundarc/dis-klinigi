<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case BANK_TRANSFER = 'bank_transfer';
    case CREDIT_CARD = 'credit_card';
    case CHECK = 'check';
    case INSURANCE = 'insurance';

    public function label(): string
    {
        return match($this) {
            self::CASH => 'Nakit',
            self::BANK_TRANSFER => 'Havale/EFT',
            self::CREDIT_CARD => 'Kredi Kartı',
            self::CHECK => 'Çek',
            self::INSURANCE => 'Sigorta',
        };
    }
}