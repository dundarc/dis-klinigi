<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'consent_kvkk_at', // --- EKSİK OLAN ALAN BUYDU ---
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
            'consent_kvkk_at' => 'datetime',
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
}
