<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Enums\TriageLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Encounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'dentist_id',
        'type',
        'triage_level',
        'arrived_at',
        'started_at',
        'ended_at',
        'status',
        'notes',
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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Encounter $encounter) {
            $encounter->treatmentPlanItems()->detach();
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(PatientTreatment::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function treatmentPlanItems(): BelongsToMany
    {
        return $this->belongsToMany(TreatmentPlanItem::class, 'encounter_treatment_plan_item')
            ->withPivot('price', 'notes', 'invoiced_at')
            ->withTimestamps();
    }

    public function hasItems(): bool
    {
        return $this->treatmentPlanItems()->exists();
    }
}