<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Enums\ConsentStatus;
use App\Models\Consent;
use App\Services\Kvkk\ConsentService;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'national_id',
        'birth_date',
        'gender',
        'phone_primary',
        'phone_secondary',
        'email',
        'address_text',
        'tax_office',
        'notes',
        'emergency_contact_person',
        'emergency_contact_phone',
        'medications_used',
        'has_private_insurance',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'gender' => Gender::class,
            'has_private_insurance' => 'boolean',
        ];
    }

    // Relationships
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function encounters()
    {
        return $this->hasMany(Encounter::class);
    }

    public function treatments()
    {
        return $this->hasMany(PatientTreatment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function consents()
    {
        return $this->hasMany(Consent::class);
    }

    public function kvkkAuditLogs()
    {
        return $this->hasMany(KvkkAuditLog::class);
    }

    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class);
    }


    public function getFirstNameAttribute($value)
    {
        if (is_array($value)) {
            return $value[0] ?? '';
        }

        return (string) $value;
    }

    public function getLastNameAttribute($value)
    {
        if (is_array($value)) {
            return $value[0] ?? '';
        }

        return (string) $value;
    }

    public function getFullNameAttribute(): string
    {
        return trim(
            collect([$this->first_name, $this->last_name])
                ->filter()
                ->implode(' ')
        );
    }

    public function latestConsent(): ?\App\Models\Consent
    {
        return ConsentService::latest(
            $this->relationLoaded('consents') ? $this : $this->loadMissing('consents')
        );
    }

    public function hasKvkkConsent(): bool
    {
        return ConsentService::hasActive(
            $this->relationLoaded('consents') ? $this : $this->loadMissing('consents')
        );
    }

    public function scopeWithActiveConsentFlag($query)
    {
        $sub = Consent::selectRaw('CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END')
            ->whereColumn('patient_id', 'patients.id')
            ->where('status', ConsentStatus::ACTIVE);

        return $query->addSelect(['has_active_consent' => $sub]);
    }
}

