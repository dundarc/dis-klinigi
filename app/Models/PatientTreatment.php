<?php

namespace App\Models;

use App\Enums\PatientTreatmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTreatment extends Model
{
    use HasFactory;

    public const DELETED_TREATMENT_LABEL = 'TEDAVİ_SİLİNDİ';

    protected $fillable = [
        'patient_id',
        'encounter_id',
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
            'performed_at' => 'datetime',
            'status' => PatientTreatmentStatus::class,
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class)->withTrashed();
    }

    public function invoiceItem()
    {
        return $this->hasOne(InvoiceItem::class);
    }

    /**
     * Encounter related to this treatment.
     */
    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    /**
     * Friendly label for soft-deleted treatments.
     */
    public function getDisplayTreatmentNameAttribute(): string
    {
        $treatment = $this->treatment;

        if (!$treatment || (method_exists($treatment, 'trashed') && $treatment->trashed())) {
            return self::DELETED_TREATMENT_LABEL;
        }

        return $treatment->name;
    }
}
