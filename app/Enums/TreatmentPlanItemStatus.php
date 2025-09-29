<?php

namespace App\Enums;

enum TreatmentPlanItemStatus: string
{
    case PLANNED = 'planned';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';
    case INVOICED = 'invoiced';

    public function label(): string
    {
        return match ($this) {
            self::PLANNED => __('Planned'),
            self::IN_PROGRESS => __('In Progress'),
            self::DONE => __('Done'),
            self::CANCELLED => __('Cancelled'),
            self::NO_SHOW => __('No Show'),
            self::INVOICED => __('Invoiced'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PLANNED => 'gray',
            self::IN_PROGRESS => 'blue',
            self::DONE => 'green',
            self::CANCELLED => 'red',
            self::NO_SHOW => 'orange',
            self::INVOICED => 'purple',
        };
    }
}
