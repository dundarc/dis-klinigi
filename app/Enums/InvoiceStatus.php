<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case UNPAID = 'unpaid';
    case PARTIAL = 'partial';
    case PAID = 'paid';
    case POSTPONED = 'ileri_tarihte_odenecek';
    case INSTALLMENT = 'taksitlendirildi';
}
