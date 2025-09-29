<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case DRAFT = 'draft';
    case ISSUED = 'issued';
    case UNPAID = 'unpaid';
    case PARTIAL = 'partial';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
    case POSTPONED = 'vadeli';
    case INSTALLMENT = 'taksitlendirildi';
    case OVERDUE = 'vadesi_gecmis';
}

