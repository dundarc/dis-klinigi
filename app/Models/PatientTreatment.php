<?php

namespace App\Models;

use App\Enums\PatientTreatmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTreatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'dentist_id',
        'treatment_id',
        'tooth_number',
        'status',
        'unit_price',
        'vat',
        'discount',
        'performed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => PatientTreatmentStatus::class,
            'performed_at' => 'datetime',
            'unit_price' => 'decimal:2',
            'vat' => 'decimal:2',
            'discount' => 'decimal:2',
        ];
    }

    /**
     * Bu tedavinin ait olduğu hasta.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Bu tedaviyi uygulayan hekim.
     */
    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    /**
     * Bu tedavinin şablonu (adı, kodu vb.).
     */
    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    /**
     * Bu tedavinin ilişkili olduğu fatura kalemi (varsa).
     * --- EKSİK OLAN İLİŞKİ BUYDU ---
     */
    public function invoiceItem()
    {
        return $this->hasOne(InvoiceItem::class);
    }
}
