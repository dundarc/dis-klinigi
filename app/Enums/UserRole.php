<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case DENTIST = 'dentist';
    case ASSISTANT = 'assistant';
    case RECEPTIONIST = 'receptionist';
        case ACCOUNTANT = 'accountant'; // Eklendi
}