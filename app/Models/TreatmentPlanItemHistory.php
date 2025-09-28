<?php

namespace App\Models;

use App\Enums\TreatmentPlanItemStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentPlanItemHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_item_id',
        'old_status',
        'new_status',
        'user_id',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'old_status' => TreatmentPlanItemStatus::class,
        'new_status' => TreatmentPlanItemStatus::class,
        'metadata' => 'array',
    ];

    public function treatmentPlanItem(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlanItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
