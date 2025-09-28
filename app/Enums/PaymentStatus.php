<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PARTIAL = 'partial';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
    case INSTALLMENT = 'installment';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Bekliyor',
            self::PARTIAL => 'Kısmi Ödeme',
            self::PAID => 'Ödendi',
            self::OVERDUE => 'Gecikmiş',
            self::INSTALLMENT => 'Taksitli',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::PARTIAL => 'blue',
            self::PAID => 'green',
            self::OVERDUE => 'red',
            self::INSTALLMENT => 'purple',
        };
    }

    public function bgClass(): string
    {
        return match($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
            self::PARTIAL => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
            self::PAID => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
            self::OVERDUE => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200',
            self::INSTALLMENT => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200',
        };
    }
}