<?php

namespace App\Models\Stock;

use App\Models\Encounter;
use App\Models\PatientTreatment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'recorded_by',
        'encounter_id',
        'patient_treatment_id',
        'used_at',
        'notes',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function patientTreatment()
    {
        return $this->belongsTo(PatientTreatment::class, 'patient_treatment_id');
    }

    public function items()
    {
        return $this->hasMany(StockUsageItem::class, 'usage_id');
    }
}
