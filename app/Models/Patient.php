<?php
namespace App\Models;

use App\Enums\AppointmentStatus;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\XRay;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

     public function xrays()
    {
        return $this->hasMany(XRay::class);
    }

    public function treatments()
{
    return $this->hasMany(PatientTreatment::class);
}


    protected $fillable = [
        'first_name', 'last_name', 'national_id', 'birth_date', 'gender',
    'phone_primary', 'phone_secondary', 'email', 'address_text', 
    'tax_office', // Eklendi
    'consent_kvkk_at', 'notes',
    'emergency_contact_person', // Eklendi
    'emergency_contact_phone', // Eklendi
    'medications_used', // Eklendi
    'has_private_insurance', // Eklendi
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
        'consent_kvkk_at',
        'notes',
        'emergency_contact_person',
        'emergency_contact_phone',
        'medications_used',
        'has_private_insurance',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'consent_kvkk_at' => 'datetime',
            'gender' => Gender::class,
        ];
    }

    // Relationships
   
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function encounters(): HasMany
    {
        return $this->hasMany(Encounter::class);
    }

   

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function consents(): HasMany
    {
        return $this->hasMany(Consent::class);
    }

    public function latestAppointment(): HasOne
    {
        return $this->hasOne(Appointment::class)->latestOfMany();
    }

    public function upcomingAppointment(): HasOne
    {
        return $this->hasOne(Appointment::class)
            ->where('start_at', '>=', now())
            ->whereIn('status', AppointmentStatus::activeForListing())
            ->orderBy('start_at');
    }

     public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([$this->first_name, $this->last_name])));
    }

}