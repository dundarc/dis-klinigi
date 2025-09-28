<?php

namespace App\Enums;

enum TreatmentPlanItemAppointmentAction: string
{
    case PLANNED = 'planned';
    case CANCELLED = 'cancelled';
    case RESCHEDULED = 'rescheduled';
    case COMPLETED = 'completed';
    case NO_SHOW = 'no_show';
    case REMOVED = 'removed';
    case UPDATED = 'updated';

    public function label(): string
    {
        return match ($this) {
            self::PLANNED => __('Planned'),
            self::CANCELLED => __('Cancelled'),
            self::RESCHEDULED => __('Rescheduled'),
            self::COMPLETED => __('Completed'),
            self::NO_SHOW => __('No Show'),
            self::REMOVED => __('Removed'),
            self::UPDATED => __('Updated'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PLANNED => 'blue',
            self::CANCELLED => 'red',
            self::RESCHEDULED => 'yellow',
            self::COMPLETED => 'green',
            self::NO_SHOW => 'red',
            self::REMOVED => 'gray',
            self::UPDATED => 'orange',
        };
    }

    /**
     * Get actions that indicate a negative outcome
     */
    public static function negativeActions(): array
    {
        return [
            self::CANCELLED,
            self::NO_SHOW,
            self::REMOVED,
        ];
    }

    /**
     * Get actions that indicate a positive outcome
     */
    public static function positiveActions(): array
    {
        return [
            self::PLANNED,
            self::COMPLETED,
            self::RESCHEDULED,
            self::UPDATED,
        ];
    }
}