<?php

namespace App\Models;

use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Enums\TriageLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'appointment_id', 'dentist_id', 'type', 'triage_level', 
        'arrived_at', 'started_at', 'ended_at', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'arrived_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'type' => EncounterType::class,
            'triage_level' => TriageLevel::class,
            'status' => EncounterStatus::class,
        ];
    }

    // --- MEVCUT İLİŞKİLER ---
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    // --- YENİ EKLENEN İLİŞKİLER ---
    /**
     * Bu ziyarette yapılan tüm tedaviler.
     */
    public function treatments()
    {
        return $this->hasMany(PatientTreatment::class);
    }

    /**
     * Bu ziyarette yazılan tüm reçeteler.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}