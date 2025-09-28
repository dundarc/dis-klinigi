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
    // DÜZELTME: Eksik olan 'vadesi_gecmis' durumu eklendi.
    case OVERDUE = 'vadesi_gecmis';
    case OVERDUE_EN = 'overdue';
}

