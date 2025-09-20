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
        'patient_id',
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

    // --- EKSİK OLAN İLİŞKİLER ---
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }
}