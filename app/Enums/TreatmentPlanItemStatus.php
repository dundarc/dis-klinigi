<?php

namespace App\Enums;

enum TreatmentPlanItemStatus: string
{
    case PLANNED = 'planned';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PLANNED => __('Planned'),
            self::IN_PROGRESS => __('In Progress'),
            self::DONE => __('Done'),
            self::CANCELLED => __('Cancelled'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PLANNED => 'gray',
            self::IN_PROGRESS => 'blue',
            self::DONE => 'green',
            self::CANCELLED => 'red',
        };
    }
}
