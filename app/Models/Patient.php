<?php
namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PatientXray;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'national_id', 'birth_date', 'gender',
    'phone_primary', 'phone_secondary', 'email', 'address_text', 
    'tax_office', // Eklendi
    'consent_kvkk_at', 'notes',
    'emergency_contact_person', // Eklendi
    'emergency_contact_phone', // Eklendi
    'medications_used', // Eklendi
    'has_private_insurance', // Eklendi
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
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function encounters() { return $this->hasMany(Encounter::class); }
    public function treatments() { return $this->hasMany(PatientTreatment::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
    public function prescriptions() { return $this->hasMany(Prescription::class); }
    public function files() { return $this->hasMany(File::class); }
    public function consents() { return $this->hasMany(Consent::class); }
 public function xrays(): HasMany
    {
        return $this->hasMany(PatientXray::class);
    }

}
