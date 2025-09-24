<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case UNPAID = 'unpaid';
    case PARTIAL = 'partial';
    case PAID = 'paid';
    case POSTPONED = 'vadeli';
    case INSTALLMENT = 'taksitlendirildi';
    // DÜZELTME: Eksik olan 'vadesi_gecmis' durumu eklendi.
    case OVERDUE = 'vadesi_gecmis';
}

