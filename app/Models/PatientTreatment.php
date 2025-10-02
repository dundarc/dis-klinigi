<?php

namespace App\Models;

use App\Enums\PatientTreatmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $patient_id
 * @property int|null $encounter_id
 * @property int|null $dentist_id
 * @property int|null $treatment_id
 * @property int|null $treatment_plan_item_id
 * @property string|null $tooth_number
 * @property PatientTreatmentStatus $status
 * @property float $unit_price
 * @property float $vat
 * @property float $discount
 * @property \Carbon\Carbon|null $performed_at
 * @property string|null $notes
 * @property string|null $display_treatment_name
 * @property-read string $display_treatment_name
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\User|null $dentist
 * @property-read \App\Models\Treatment|null $treatment
 * @property-read \App\Models\InvoiceItem|null $invoiceItem
 * @property-read \App\Models\Encounter|null $encounter
 * @property-read \App\Models\TreatmentPlanItem|null $treatmentPlanItem
 */
class PatientTreatment extends Model
{
    use HasFactory;

    public const DELETED_TREATMENT_LABEL = 'TEDAVİ_SİLİNDİ';

    protected $fillable = [
        'patient_id',
        'encounter_id',
        'dentist_id',
        'treatment_id',
        'treatment_plan_item_id',
        'tooth_number',
        'status',
        'unit_price',
        'vat',
        'discount',
        'performed_at',
        'notes',
        'display_treatment_name',
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
     * Treatment plan item that this treatment was created from (if applicable).
     */
    public function treatmentPlanItem()
    {
        return $this->belongsTo(TreatmentPlanItem::class);
    }

    /**
     * Friendly label for treatments - prioritizes stored display name over relationship lookup.
     */
    public function getDisplayTreatmentNameAttribute(): string
    {
        // First, check if we have a stored display_treatment_name in the database
        if (!empty($this->attributes['display_treatment_name'])) {
            return $this->attributes['display_treatment_name'];
        }
        
        // Fallback to treatment relationship lookup
        $treatment = $this->treatment;

        if (!$treatment || (method_exists($treatment, 'trashed') && $treatment->trashed())) {
            return self::DELETED_TREATMENT_LABEL;
        }

        return $treatment->name;
    }
}
