<?php

namespace App\Enums;

enum EncounterType: string
{
    case SCHEDULED = 'scheduled';
    case WALK_IN = 'walk_in';
    case EMERGENCY = 'emergency';
}