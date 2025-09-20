<?php

namespace App\Enums;

enum EncounterStatus: string
{
    case WAITING = 'waiting';
    case IN_SERVICE = 'in_service';
    case DONE = 'done';
    case CANCELLED = 'cancelled';
}