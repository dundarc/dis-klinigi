<?php

namespace App\Enums;

enum TriageLevel: string
{
    case RED = 'red'; // Critical
    case YELLOW = 'yellow'; // Urgent
    case GREEN = 'green'; // Non-urgent

    public function label(): string
    {
        return match($this) {
            self::RED => 'Kritik',
            self::YELLOW => 'Acil',
            self::GREEN => 'Normal',
        };
    }
}