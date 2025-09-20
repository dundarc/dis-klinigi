<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    // Bu tablonun migration'ında created_at/updated_at oluşturmamıştık.
    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'patient_treatment_id',
        'description',
        'qty',
        'unit_price',
        'vat',
        'line_total',
    ];

    /**
     * Bu fatura kaleminin ait olduğu ana fatura.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Bu fatura kaleminin ilişkili olduğu hasta tedavisi.
     * --- EKSİK OLAN İLİŞKİ BUYDU ---
     */
    public function patientTreatment()
    {
        return $this->belongsTo(PatientTreatment::class);
    }
}