<?php

namespace App\Models;

use App\Enums\TreatmentPlanItemAppointmentAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentPlanItemAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_item_id',
        'appointment_id',
        'action',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'action' => TreatmentPlanItemAppointmentAction::class,
    ];

    public function treatmentPlanItem(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlanItem::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
