<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'action',
        'note',
        'user_id',
    ];

    public function invoice()
    {
        return $this->belongsTo(StockPurchaseInvoice::class, 'invoice_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}