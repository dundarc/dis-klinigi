<?php

namespace App\Enums;

enum MovementDirection: string
{
    case IN = 'in';
    case OUT = 'out';
    case ADJUSTMENT = 'adjustment';

    public function label(): string
    {
        return match($this) {
            self::IN => 'Giriş',
            self::OUT => 'Çıkış',
            self::ADJUSTMENT => 'Düzeltme',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::IN => 'green',
            self::OUT => 'red',
            self::ADJUSTMENT => 'blue',
        };
    }

    public function bgClass(): string
    {
        return match($this) {
            self::IN => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
            self::OUT => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200',
            self::ADJUSTMENT => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::IN => 'arrow-up',
            self::OUT => 'arrow-down',
            self::ADJUSTMENT => 'pencil',
        };
    }
}