<?php


namespace App\Models;

use App\Enums\TreatmentPlanItemStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TreatmentPlanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_id',
        'treatment_id',
        'dentist_id',
        'appointment_id',
        'tooth_number',
        'estimated_price',
        'status',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'status' => TreatmentPlanItemStatus::class,
        'estimated_price' => 'decimal:2',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function treatmentPlan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function invoiceItem(): HasOne
    {
        return $this->hasOne(InvoiceItem::class, 'treatment_plan_item_id');
    }

    public function appointmentHistory(): HasMany
    {
        return $this->hasMany(TreatmentPlanItemAppointment::class);
    }

    public function encounters(): BelongsToMany
    {
        return $this->belongsToMany(Encounter::class, 'encounter_treatment_plan_item')
            ->withPivot('price', 'notes', 'invoiced_at')
            ->withTimestamps();
    }

    public function histories(): HasMany
    {
        return $this->hasMany(TreatmentPlanItemHistory::class)->orderBy('created_at', 'desc');
    }

    public function isDone(): bool
    {
        return $this->status === TreatmentPlanItemStatus::DONE;
    }

    /**
     * Update the status of the treatment plan item and log the change.
     *
     * @param TreatmentPlanItemStatus $newStatus
     * @param User|null $user
     * @param string|null $notes
     * @param array|null $metadata
     * @return bool
     */
    public function changeStatus(TreatmentPlanItemStatus $newStatus, ?User $user = null, ?string $notes = null, ?array $metadata = null): bool
    {
        $oldStatus = $this->status;

        if ($oldStatus === $newStatus) {
            return true; // No change needed
        }

        $this->status = $newStatus;

        // Set timestamps based on status
        if ($newStatus === TreatmentPlanItemStatus::DONE) {
            $this->completed_at = now();
        } elseif ($newStatus === TreatmentPlanItemStatus::CANCELLED) {
            $this->cancelled_at = now();
        }

        $saved = $this->save();

        if ($saved) {
            // Log the status change
            $this->histories()->create([
                'old_status' => $oldStatus->value,
                'new_status' => $newStatus->value,
                'user_id' => $user?->id,
                'notes' => $notes,
                'metadata' => $metadata,
            ]);

            // Update treatment plan total cost if cancelled
            if ($newStatus === TreatmentPlanItemStatus::CANCELLED) {
                $this->updatePlanTotalCost();
            }
        }

        return $saved;
    }

    protected static function booted(): void
    {
        static::creating(function (self $item): void {
            if (! $item->dentist_id) {
                $item->dentist_id = static::resolveDefaultDentistId($item);
            }
        });

        static::updating(function (self $item): void {
            if ($item->isDirty('appointment_id') && $item->appointment_id && ! $item->dentist_id) {
                $item->dentist_id = static::resolveDefaultDentistId($item);
            }
        });
    }

    protected static function resolveDefaultDentistId(self $item): ?int
    {
        if ($item->relationLoaded('appointment') && $item->appointment) {
            return $item->appointment?->dentist_id;
        }

        if ($item->appointment_id) {
            $appointment = Appointment::query()->find($item->appointment_id);
            if ($appointment) {
                return $appointment->dentist_id;
            }
        }

        if ($item->relationLoaded('treatmentPlan') && $item->treatmentPlan) {
            return $item->treatmentPlan->dentist_id;
        }

        if ($item->treatment_plan_id) {
            return TreatmentPlan::query()->whereKey($item->treatment_plan_id)->value('dentist_id');
        }

        return null;
    }

    /**
     * Ensure the treatment plan item is linked to an encounter when marked as DONE.
     *
     * @param User|null $user
     * @param array|null $metadata
     * @return void
     */
    private function ensureEncounterLinkage(?User $user = null, ?array $metadata = null): void
    {
        // If already linked to encounters, no need to do anything
        if ($this->encounters()->exists()) {
            return;
        }

        $encounter = null;

        // Try to find an existing encounter to link to
        if ($this->appointment && $this->appointment->encounter) {
            // Link to the appointment's encounter
            $encounter = $this->appointment->encounter;
        } else {
            // Try to find a recent encounter for this patient on the same day
            $encounter = \App\Models\Encounter::where('patient_id', $this->treatmentPlan->patient_id)
                ->whereDate('created_at', today())
                ->where('status', \App\Enums\EncounterStatus::DONE)
                ->latest()
                ->first();

            // If no encounter found today, create a new one
            if (!$encounter) {
                $encounter = \App\Models\Encounter::create([
                    'patient_id' => $this->treatmentPlan->patient_id,
                    'appointment_id' => $this->appointment_id,
                    'dentist_id' => $this->treatmentPlan->dentist_id ?? $user?->id,
                    'type' => \App\Enums\EncounterType::WALK_IN,
                    'status' => \App\Enums\EncounterStatus::DONE,
                    'arrived_at' => now(),
                    'started_at' => now(),
                    'ended_at' => now(),
                    'notes' => 'Otomatik olarak tedavi kalemi tamamlanmasÄ±ndan oluÅŸturuldu.',
                ]);
            }
        }

        // Link the treatment plan item to the encounter
        if ($encounter) {
            $this->encounters()->attach($encounter->id, [
                'price' => $this->estimated_price,
                'notes' => 'Tedavi kalemi tamamlanmasÄ±ndan otomatik olarak baÄŸlandÄ±.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Update the treatment plan's total estimated cost when an item is cancelled.
     */
    private function updatePlanTotalCost(): void
    {
        $plan = $this->treatmentPlan;
        if ($plan) {
            $activeItemsCost = $plan->items()
                ->where('status', '!=', TreatmentPlanItemStatus::CANCELLED)
                ->sum('estimated_price');

            $plan->update(['total_estimated_cost' => $activeItemsCost]);
        }
    }
}
