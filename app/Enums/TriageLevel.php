<?php

namespace App\Enums;

enum TriageLevel: string
{
    case RED = 'red'; // Critical
    case YELLOW = 'yellow'; // Urgent
    case GREEN = 'green'; // Non-urgent
}