<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Enums\TreatmentPlanItemAppointmentAction;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TreatmentPlanService
{
    /**
     * Create a new treatment plan.
     *
     * @param Patient $patient
     * @param User $dentist
     * @param array $items
     * @param string|null $notes
     * @return TreatmentPlan
     */
    public function createPlan(Patient $patient, User $dentist, array $items, ?string $notes): TreatmentPlan
    {
        return DB::transaction(function () use ($patient, $dentist, $items, $notes) {
            $totalCost = array_sum(array_column($items, 'estimated_price'));

            $plan = TreatmentPlan::create([
                'patient_id' => $patient->id,
                'dentist_id' => $dentist->id,
                'notes' => $notes,
                'total_estimated_cost' => $totalCost,
                'status' => \App\Enums\TreatmentPlanStatus::DRAFT,
            ]);

            foreach ($items as $itemData) {
                $appointmentId = null;
                if (!empty($itemData['appointment_date'])) {
                    $appointment = Appointment::create([
                        'patient_id' => $patient->id,
                        'dentist_id' => $dentist->id,
                        'start_at' => $itemData['appointment_date'],
                        'end_at' => Carbon::parse($itemData['appointment_date'])->addMinutes(30),
                        'status' => AppointmentStatus::SCHEDULED,
                        'notes' => 'Tedavi planından oluşturuldu.' // Created from treatment plan.
                    ]);
                    $appointmentId = $appointment->id;
                }

                $plan->items()->create([
                    'treatment_id' => $itemData['treatment_id'],
                    'tooth_number' => $itemData['tooth_number'],
                    'appointment_id' => $appointmentId,
                    'estimated_price' => $itemData['estimated_price'],
                    'status' => \App\Enums\TreatmentPlanItemStatus::PLANNED,
                ]);
            }

            return $plan;
        });
    }

    public function updatePlan(TreatmentPlan $plan, array $data): TreatmentPlan
    {
        return DB::transaction(function () use ($plan, $data) {
            // Null relation safety check - dentist_id kontrolü
            if (empty($data['dentist_id'])) {
                throw new \InvalidArgumentException('Dentist ID is required for treatment plan update');
            }

            // Dentist varlığını kontrol et
            $dentist = \App\Models\User::find($data['dentist_id']);
            if (!$dentist) {
                throw new \InvalidArgumentException('Selected dentist not found');
            }

            $plan->update([
                'dentist_id' => $data['dentist_id'],
                'status' => $data['status'] ?? $plan->status,
                'notes' => $data['notes'] ?? null,
            ]);

            // Handle deleted items first
            if (!empty($data['deleted_items'])) {
                foreach ($data['deleted_items'] as $itemId) {
                    $item = TreatmentPlanItem::find($itemId);
                    if ($item && $item->treatment_plan_id === $plan->id) {
                        // İş kuralı: Tamamlanan öğeler düzenlenemez
                        if ($item->status === \App\Enums\TreatmentPlanItemStatus::DONE) {
                            throw new \InvalidArgumentException('Tamamlanan tedavi kalemleri düzenlenemez veya silinemez.');
                        }

                        // Randevulu item silinirse randevu yönetimi
                        $this->detachItemFromAppointment($item, 'Item removed from treatment plan');

                        // Detach from encounters safely
                        if ($item->encounters()->exists()) {
                            $item->encounters()->detach();
                        }

                        // Log status change to cancelled and delete
                        $item->changeStatus(
                            \App\Enums\TreatmentPlanItemStatus::CANCELLED,
                            auth()->user(),
                            'Removed from treatment plan',
                            ['treatment_plan_update' => true]
                        );

                        $item->delete();
                    }
                }
            }

            $totalCost = 0;

            // Handle existing items
            if (!empty($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    // Validate required fields for each item
                    if (empty($itemData['treatment_id']) || !isset($itemData['estimated_price'])) {
                        throw new \InvalidArgumentException('Treatment ID and estimated price are required for all items');
                    }

                    // Handle existing items
                    if (isset($itemData['id']) && $itemData['id']) {
                        $existingItem = TreatmentPlanItem::find($itemData['id']);
                        if ($existingItem && $existingItem->treatment_plan_id === $plan->id) {
                            // İş kuralı: Tamamlanan öğeler düzenlenemez
                            if ($existingItem->status === \App\Enums\TreatmentPlanItemStatus::DONE) {
                                throw new \InvalidArgumentException('Tamamlanan tedavi kalemleri düzenlenemez.');
                            }

                            $oldAppointmentId = $existingItem->appointment_id;

                            // Randevu tarihi değişirse eski iptal, yeni oluştur
                            $appointmentId = $this->rescheduleItemAppointment(
                                $existingItem,
                                $itemData['appointment_date'] ?? null,
                                $data['dentist_id'],
                                $plan->patient_id
                            );

                            // Update existing item
                            $existingItem->update([
                                'treatment_id' => $itemData['treatment_id'],
                                'tooth_number' => $itemData['tooth_number'] ?? null,
                                'appointment_id' => $appointmentId,
                                'estimated_price' => (float) $itemData['estimated_price'],
                                'status' => $itemData['status'] ?? \App\Enums\TreatmentPlanItemStatus::PLANNED,
                            ]);
                            $planItem = $existingItem;
                        } else {
                            // Create new item if ID doesn't exist or doesn't belong to this plan
                            $appointmentId = null;
                            if (!empty($itemData['appointment_date'])) {
                                $appointmentId = $this->createAppointmentForItem(
                                    $plan->patient_id,
                                    $data['dentist_id'],
                                    $itemData['appointment_date']
                                );
                            }

                            $planItem = TreatmentPlanItem::create([
                                'treatment_plan_id' => $plan->id,
                                'treatment_id' => $itemData['treatment_id'],
                                'tooth_number' => $itemData['tooth_number'] ?? null,
                                'appointment_id' => $appointmentId,
                                'estimated_price' => (float) $itemData['estimated_price'],
                                'status' => $itemData['status'] ?? \App\Enums\TreatmentPlanItemStatus::PLANNED,
                            ]);

                            if ($appointmentId) {
                                \App\Models\TreatmentPlanItemAppointment::create([
                                    'treatment_plan_item_id' => $planItem->id,
                                    'appointment_id' => $appointmentId,
                                    'action' => TreatmentPlanItemAppointmentAction::PLANNED,
                                    'notes' => 'Appointment planned for new treatment plan item',
                                    'user_id' => auth()->id(),
                                ]);
                            }
                        }
                    } else {
                        // Create new item - Yeni öğe ekleme + tarih varsa randevu oluştur
                        $appointmentId = null;
                        \Illuminate\Support\Facades\Log::info('Yeni öğe için randevu kontrolü', [
                            'treatment_id' => $itemData['treatment_id'] ?? null,
                            'appointment_date' => $itemData['appointment_date'] ?? null,
                            'estimated_price' => $itemData['estimated_price'] ?? null
                        ]);

                        if (!empty($itemData['appointment_date'])) {
                            \Illuminate\Support\Facades\Log::info('Yeni öğe için randevu oluşturuluyor', [
                                'appointment_date' => $itemData['appointment_date']
                            ]);
                            $appointmentId = $this->createAppointmentForItem(
                                $plan->patient_id,
                                $data['dentist_id'],
                                $itemData['appointment_date']
                            );
                        } else {
                            \Illuminate\Support\Facades\Log::info('Yeni öğe için randevu tarihi yok');
                        }

                        $planItem = TreatmentPlanItem::create([
                            'treatment_plan_id' => $plan->id,
                            'treatment_id' => $itemData['treatment_id'],
                            'tooth_number' => $itemData['tooth_number'] ?? null,
                            'appointment_id' => $appointmentId,
                            'estimated_price' => (float) $itemData['estimated_price'],
                            'status' => $itemData['status'] ?? \App\Enums\TreatmentPlanItemStatus::PLANNED,
                        ]);

                        if ($appointmentId) {
                            \App\Models\TreatmentPlanItemAppointment::create([
                                'treatment_plan_item_id' => $planItem->id,
                                'appointment_id' => $appointmentId,
                                'action' => TreatmentPlanItemAppointmentAction::PLANNED,
                                'notes' => 'Appointment planned for new treatment plan item',
                                'user_id' => auth()->id(),
                            ]);
                        }
                    }

                    $totalCost += $planItem->estimated_price;
                }
            }

            // Handle new items (legacy support)
            if (!empty($data['new_items'])) {
                foreach ($data['new_items'] as $newItem) {
                    if (!empty($newItem['treatment_id'])) {
                        $planItem = $this->createNewPlanItem($plan, $newItem, $data['dentist_id']);
                        $totalCost += $newItem['estimated_price'];
                    }
                }
            }

            $plan->total_estimated_cost = $totalCost;
            $plan->save();

            return $plan;
        });
    }

    /**
     * Create a new treatment plan item
     */
    private function createNewPlanItem(TreatmentPlan $plan, array $itemData, int $dentistId): TreatmentPlanItem
    {
        $appointmentId = null;
        
        // Handle appointment creation if date provided
        if (!empty($itemData['appointment_date'])) {
            $appointment = Appointment::create([
                'patient_id' => $plan->patient_id,
                'dentist_id' => $dentistId,
                'start_at' => $itemData['appointment_date'],
                'end_at' => Carbon::parse($itemData['appointment_date'])->addMinutes(30),
                'status' => AppointmentStatus::SCHEDULED,
                'notes' => 'Tedavi planından oluşturuldu.'
            ]);
            $appointmentId = $appointment->id;
        }

        $planItem = TreatmentPlanItem::create([
            'treatment_plan_id' => $plan->id,
            'treatment_id' => $itemData['treatment_id'],
            'tooth_number' => $itemData['tooth_number'],
            'appointment_id' => $appointmentId,
            'estimated_price' => $itemData['estimated_price'],
            'status' => \App\Enums\TreatmentPlanItemStatus::PLANNED,
        ]);

        // Log appointment history if appointment was created
        if ($appointmentId) {
            \App\Models\TreatmentPlanItemAppointment::create([
                'treatment_plan_item_id' => $planItem->id,
                'appointment_id' => $appointmentId,
                'action' => 'planned',
                'notes' => 'Appointment planned for new treatment plan item',
                'user_id' => auth()->id(),
            ]);
        }

        return $planItem;
    }

    /**
     * Assign treatment plan items to specific appointments.
     *
     * @param TreatmentPlan $plan
     * @param array $appointments
     * @return void
     */
    public function assignToAppointments(TreatmentPlan $plan, array $appointments): void
    {
        // Logic to link items to appointments will be implemented here.
    }

    /**
     * Mark a treatment plan item as done and link to encounter.
     *
     * @param int $itemId
     * @param int $encounterId
     * @param User|null $user
     * @return bool
     */
    public function markItemAsDone(int $itemId, int $encounterId, ?User $user = null): bool
    {
        $item = TreatmentPlanItem::find($itemId);
        $encounter = \App\Models\Encounter::find($encounterId);

        if (!$item || !$encounter) {
            return false;
        }

        // Update item status
        $oldStatus = $item->status;
        $item->status = \App\Enums\TreatmentPlanItemStatus::DONE;
        $item->completed_at = now();
        $item->save();

        // Log the status change
        $item->histories()->create([
            'old_status' => $oldStatus->value,
            'new_status' => \App\Enums\TreatmentPlanItemStatus::DONE->value,
            'user_id' => $user?->id,
            'notes' => 'Completed during encounter #' . $encounterId,
            'metadata' => ['encounter_id' => $encounterId],
        ]);

        // Check if the relationship already exists before creating it
        $existingLink = $encounter->treatmentPlanItems()->where('treatment_plan_item_id', $item->id)->exists();
        
        if (!$existingLink) {
            // Link the treatment plan item to the encounter
            $encounter->treatmentPlanItems()->attach($itemId, [
                'price' => $item->estimated_price,
                'notes' => 'Completed during encounter',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            \Illuminate\Support\Facades\Log::info('Treatment plan item linked to encounter via markItemAsDone', [
                'encounter_id' => $encounter->id,
                'treatment_plan_item_id' => $item->id,
                'price' => $item->estimated_price,
                'user_id' => $user?->id,
            ]);
        } else {
            \Illuminate\Support\Facades\Log::info('Treatment plan item already linked to encounter, skipping duplicate in markItemAsDone', [
                'encounter_id' => $encounter->id,
                'treatment_plan_item_id' => $item->id,
                'user_id' => $user?->id,
            ]);
        }

        // Create or assign appointment if item doesn't have one
        if (!$item->appointment_id) {
            if ($encounter->appointment_id) {
                // Use the encounter's appointment
                $item->update(['appointment_id' => $encounter->appointment_id]);
                $item->appointment_id = $encounter->appointment_id; // Update the object
            } else {
                // Create a new appointment
                $appointment = Appointment::create([
                    'patient_id' => $item->treatmentPlan->patient_id,
                    'dentist_id' => $encounter->dentist_id,
                    'start_at' => $encounter->started_at ?? $encounter->arrived_at ?? now(),
                    'end_at' => ($encounter->started_at ?? $encounter->arrived_at ?? now())->addMinutes(30),
                    'status' => AppointmentStatus::COMPLETED,
                    'notes' => 'Otomatik olarak tedavi tamamlanmasından oluşturuldu.',
                ]);
                $item->update(['appointment_id' => $appointment->id]);
                $item->appointment_id = $appointment->id; // Update the object
            }
        }

        // Create appointment history entry
        \App\Models\TreatmentPlanItemAppointment::create([
            'treatment_plan_item_id' => $itemId,
            'appointment_id' => $item->appointment_id,
            'action' => TreatmentPlanItemAppointmentAction::COMPLETED,
            'notes' => 'Treatment completed during encounter #' . $encounterId,
            'user_id' => $user?->id,
        ]);

        return true;
    }

    /**
     * Update treatment plan item status based on appointment status.
     *
     * @param int $appointmentId
     * @return void
     */
    public function updateItemStatusFromAppointment(int $appointmentId, ?User $user = null): void
    {
        $appointment = Appointment::find($appointmentId);
        if (!$appointment) {
            return;
        }

        $items = TreatmentPlanItem::where('appointment_id', $appointmentId)->get();

        foreach ($items as $item) {
            $newStatus = match($appointment->status) {
                AppointmentStatus::COMPLETED => \App\Enums\TreatmentPlanItemStatus::DONE,
                AppointmentStatus::NO_SHOW => \App\Enums\TreatmentPlanItemStatus::CANCELLED,
                AppointmentStatus::CANCELLED => \App\Enums\TreatmentPlanItemStatus::CANCELLED,
                default => $item->status
            };

            if ($newStatus !== $item->status) {
                $notes = 'Appointment status updated to ' . $appointment->status->label();
                $item->changeStatus($newStatus, $user, $notes, ['appointment_id' => $appointmentId]);
            }

            // Create appointment history entry
            $action = match($appointment->status) {
                AppointmentStatus::COMPLETED => TreatmentPlanItemAppointmentAction::COMPLETED,
                AppointmentStatus::NO_SHOW => TreatmentPlanItemAppointmentAction::NO_SHOW,
                AppointmentStatus::CANCELLED => TreatmentPlanItemAppointmentAction::CANCELLED,
                default => TreatmentPlanItemAppointmentAction::UPDATED
            };

            \App\Models\TreatmentPlanItemAppointment::create([
                'treatment_plan_item_id' => $item->id,
                'appointment_id' => $appointmentId,
                'action' => $action,
                'notes' => 'Appointment status updated to ' . $appointment->status->label(),
                'user_id' => $user?->id,
            ]);
        }
    }

    /**
     * Get treatment plan items that can be assigned to appointments.
     *
     * @param int $patientId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableItemsForPatient(int $patientId): \Illuminate\Database\Eloquent\Collection
    {
        return TreatmentPlanItem::whereHas('treatmentPlan', function ($query) use ($patientId) {
            $query->where('patient_id', $patientId)
                  ->where('status', \App\Enums\TreatmentPlanStatus::ACTIVE);
        })
        ->whereIn('status', [\App\Enums\TreatmentPlanItemStatus::PLANNED, \App\Enums\TreatmentPlanItemStatus::IN_PROGRESS])
        ->with(['treatment', 'treatmentPlan'])
        ->get();
    }

    /**
     * Assign treatment plan item to an appointment.
     *
     * @param int $itemId
     * @param int $appointmentId
     * @return bool
     */
    public function assignItemToAppointment(int $itemId, int $appointmentId, ?User $user = null): bool
    {
        $item = TreatmentPlanItem::find($itemId);
        $appointment = Appointment::find($appointmentId);

        if (!$item || !$appointment) return false;

        // Check if appointment belongs to the same patient
        if ($appointment->patient_id !== $item->treatmentPlan->patient_id) return false;

        $item->update(['appointment_id' => $appointmentId]);

        // Change status to in_progress
        $item->changeStatus(
            \App\Enums\TreatmentPlanItemStatus::IN_PROGRESS,
            $user,
            'Assigned to appointment #' . $appointmentId,
            ['appointment_id' => $appointmentId]
        );

        return true;
    }

    /**
     * Generate an invoice from items linked to an encounter.
     *
     * @param \App\Models\Encounter $encounter
     * @param bool $onlyNotYetInvoiced
     * @return \App\Models\Invoice|null
     */
    public function generateInvoiceFromEncounterItems(\App\Models\Encounter $encounter, bool $onlyNotYetInvoiced = true): ?\App\Models\Invoice
    {
        $query = $encounter->treatmentPlanItems();

        if ($onlyNotYetInvoiced) {
            $query->whereNull('encounter_treatment_plan_item.invoiced_at');
        }

        $items = $query->get();

        if ($items->isEmpty()) return null;

        // Generate invoice number
        $invoiceNo = 'ENC-' . $encounter->id . '-' . now()->format('YmdHis');

        // Calculate totals
        $subtotal = $items->sum(function ($item) {
            return $item->pivot->price;
        });
        $vatRate = 0.18; // 18% KDV
        $vatTotal = $subtotal * $vatRate;
        $grandTotal = $subtotal + $vatTotal;

        // Create invoice
        $invoice = \App\Models\Invoice::create([
            'patient_id' => $encounter->patient_id,
            'invoice_no' => $invoiceNo,
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => $subtotal,
            'vat_total' => $vatTotal,
            'discount_total' => 0,
            'grand_total' => $grandTotal,
            'status' => \App\Enums\InvoiceStatus::ISSUED,
            'notes' => 'Muayene faturası - Muayene #' . $encounter->id,
        ]);

        // Create invoice items and update pivot
        foreach ($items as $item) {
            $unitPrice = $item->pivot->price;
            $lineTotal = $unitPrice * (1 + $vatRate);

            $invoiceItem = \App\Models\InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'patient_treatment_id' => null,
                'description' => $item->treatment->name . ($item->tooth_number ? ' (Diş ' . $item->tooth_number . ')' : ''),
                'quantity' => 1,
                'unit_price' => $unitPrice,
                'vat_rate' => $vatRate * 100,
                'line_total' => $lineTotal,
            ]);

            // Update pivot with invoiced_at and invoice_item_id
            $encounter->treatmentPlanItems()->updateExistingPivot($item->id, [
                'invoiced_at' => now(),
                'invoice_item_id' => $invoiceItem->id,
            ]);
        }

        return $invoice;
    }

    /**
     * Ensure a treatment plan item is linked to an encounter when marked as DONE.
     *
     * @param TreatmentPlanItem $item
     * @return void
     */
    public function ensureEncounterLinkageForItem(TreatmentPlanItem $item): void
    {
        // If already linked to encounters, no need to do anything
        if ($item->encounters()->exists()) {
            return;
        }

        $encounter = null;

        // Try to find an existing encounter to link to
        if ($item->appointment && $item->appointment->encounter) {
            // Link to the appointment's encounter
            $encounter = $item->appointment->encounter;
        } else {
            // Try to find a recent encounter for this patient on the same day
            $encounter = \App\Models\Encounter::where('patient_id', $item->treatmentPlan->patient_id)
                ->whereDate('created_at', today())
                ->where('status', \App\Enums\EncounterStatus::DONE)
                ->latest()
                ->first();

            // If no encounter found today, create a new one
            if (!$encounter) {
                $encounter = \App\Models\Encounter::create([
                    'patient_id' => $item->treatmentPlan->patient_id,
                    'appointment_id' => $item->appointment_id,
                    'dentist_id' => $item->treatmentPlan->dentist_id,
                    'type' => \App\Enums\EncounterType::WALK_IN,
                    'status' => \App\Enums\EncounterStatus::DONE,
                    'arrived_at' => now(),
                    'started_at' => now(),
                    'ended_at' => now(),
                    'notes' => 'Otomatik olarak tedavi kalemi tamamlanmasından oluşturuldu.',
                ]);
            }
        }

        // Link the treatment plan item to the encounter
        if ($encounter) {
            $item->encounters()->attach($encounter->id, [
                'price' => $item->estimated_price,
                'notes' => 'Tedavi kalemi tamamlanmasından otomatik olarak bağlandı.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Randevu tarihi değişirse eski randevuyu iptal et ve yeni randevu oluştur/güncelle
     */
    private function rescheduleItemAppointment(TreatmentPlanItem $item, ?string $newDate, int $dentistId, int $patientId): ?int
    {
        $oldAppointmentId = $item->appointment_id;

        if (!empty($newDate)) {
            // Randevu çakışma kontrolü
            $appointmentDate = Carbon::parse($newDate);
            if ($appointmentDate->isPast()) {
                throw new \InvalidArgumentException('Appointment date must be in the future');
            }

            // Çakışma kontrolü
            if ($this->checkAppointmentConflict($dentistId, $newDate, $appointmentDate->addMinutes(30)->format('Y-m-d H:i:s'), $oldAppointmentId)) {
                throw new \InvalidArgumentException('Bu tarih ve saatte doktorun başka randevusu var.');
            }

            if ($item->appointment) {
                // Mevcut randevuyu güncelle
                $appointment = $item->appointment;
                $appointment->update([
                    'patient_id' => $patientId,
                    'dentist_id' => $dentistId,
                    'start_at' => $newDate,
                    'end_at' => $appointmentDate->addMinutes(30),
                    'status' => AppointmentStatus::SCHEDULED,
                ]);
                $appointmentId = $appointment->id;

                // Log appointment update
                \App\Models\TreatmentPlanItemAppointment::create([
                    'treatment_plan_item_id' => $item->id,
                    'appointment_id' => $appointmentId,
                    'action' => TreatmentPlanItemAppointmentAction::UPDATED,
                    'notes' => 'Appointment rescheduled during treatment plan update',
                    'user_id' => auth()->id(),
                ]);
            } else {
                // Yeni randevu oluştur
                $appointmentId = $this->createAppointmentForItem($patientId, $dentistId, $newDate);

                // Log appointment creation
                \App\Models\TreatmentPlanItemAppointment::create([
                    'treatment_plan_item_id' => $item->id,
                    'appointment_id' => $appointmentId,
                    'action' => TreatmentPlanItemAppointmentAction::PLANNED,
                    'notes' => 'Appointment planned during treatment plan update',
                    'user_id' => auth()->id(),
                ]);
            }
        } else {
            // Randevu tarihi kaldırıldı - mevcut randevuyu ayır
            if ($oldAppointmentId) {
                $this->detachItemFromAppointment($item, 'Appointment date cleared during treatment plan update');
            }
            $appointmentId = null;
        }

        return $appointmentId;
    }

    /**
     * Item'ı randevudan ayır ve gerekirse randevuyu iptal et
     */
    private function detachItemFromAppointment(TreatmentPlanItem $item, string $reason): void
    {
        if (!$item->appointment_id) {
            return;
        }

        $appointment = $item->appointment;
        if (!$appointment) {
            return;
        }

        // Log appointment removal
        \App\Models\TreatmentPlanItemAppointment::create([
            'treatment_plan_item_id' => $item->id,
            'appointment_id' => $appointment->id,
            'action' => TreatmentPlanItemAppointmentAction::REMOVED,
            'notes' => $reason,
            'user_id' => auth()->id(),
        ]);

        // Check if appointment has other items
        $remainingItems = TreatmentPlanItem::where('appointment_id', $appointment->id)
            ->where('id', '!=', $item->id)
            ->count();

        if ($remainingItems === 0) {
            // Randevuda başka item kalmadı, iptal et
            $this->cancelAppointmentWithReason($appointment, 'Tedavi öğeleri kaldırıldı - otomatik iptal edildi');
        }

        // Item'ı randevudan ayır
        $item->update(['appointment_id' => null]);
    }

    /**
     * Randevuyu açıklama ile iptal et
     */
    private function cancelAppointmentWithReason(Appointment $appointment, string $reason): void
    {
        $appointment->update([
            'status' => AppointmentStatus::CANCELLED,
            'cancelled_at' => now(),
            'notes' => ($appointment->notes ? $appointment->notes . ' | ' : '') . $reason
        ]);
    }

    /**
     * Yeni öğe için randevu oluştur
     */
    private function createAppointmentForItem(int $patientId, int $dentistId, string $dateTime): int
    {
        $appointmentDate = Carbon::parse($dateTime);

        // Çakışma kontrolü
        if ($this->checkAppointmentConflict($dentistId, $dateTime, $appointmentDate->addMinutes(30)->format('Y-m-d H:i:s'))) {
            throw new \InvalidArgumentException('Bu tarih ve saatte doktorun başka randevusu var.');
        }

        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'dentist_id' => $dentistId,
            'start_at' => $dateTime,
            'end_at' => $appointmentDate->addMinutes(30),
            'status' => AppointmentStatus::SCHEDULED,
            'notes' => 'Tedavi planından oluşturuldu.'
        ]);

        return $appointment->id;
    }

    /**
     * Randevu çakışma kontrolü - doktor bazında
     */
    public function checkAppointmentConflict(int $dentistId, string $startAt, string $endAt, ?int $ignoreId = null): bool
    {
        $query = Appointment::where('dentist_id', $dentistId)
            ->where('status', '!=', AppointmentStatus::CANCELLED)
            ->where(function ($q) use ($startAt, $endAt) {
                $q->whereBetween('start_at', [$startAt, $endAt])
                  ->orWhereBetween('end_at', [$startAt, $endAt])
                  ->orWhere(function ($q2) use ($startAt, $endAt) {
                      $q2->where('start_at', '<=', $startAt)
                         ->where('end_at', '>=', $endAt);
                  });
            });

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }






    /**
     * Get cost summary for a treatment plan.
     *
     * @param TreatmentPlan $plan
     * @return array
     */
    public function getCostSummary(TreatmentPlan $plan): array
    {
        $items = $plan->items;

        $totalEstimated = $items->sum('estimated_price');

        // Calculate actual invoiced and paid amounts
        $totalActual = 0;
        $totalPaid = 0;

        // First, handle Method 1: Invoices through PatientTreatment records (existing method)
        foreach ($items as $item) {
            // Find all patient treatments for this treatment plan item
            $patientTreatments = \App\Models\PatientTreatment::where('treatment_plan_item_id', $item->id)->get();

            // Find all invoice items for these patient treatments
            $invoiceItemsFromTreatments = \App\Models\InvoiceItem::whereIn('patient_treatment_id', $patientTreatments->pluck('id'))
                ->whereHas('invoice', function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->with('invoice.payments')
                ->get();

            foreach ($invoiceItemsFromTreatments as $invoiceItem) {
                $totalActual += $invoiceItem->line_total;

                // Calculate paid amount for this invoice item
                $invoice = $invoiceItem->invoice;
                if ($invoice) {
                    $totalPaidForInvoice = $invoice->payments->sum('amount');
                    $itemRatio = $invoiceItem->line_total / $invoice->grand_total;
                    $totalPaid += $totalPaidForInvoice * $itemRatio;
                }
            }
        }


        $activeItems = $items->whereNotIn('status', [\App\Enums\TreatmentPlanItemStatus::CANCELLED]);

        return [
            'total_estimated' => $totalEstimated,
            'total_actual' => $totalActual,
            'total_paid' => min($totalPaid, $totalActual), // Don't exceed total invoiced amount
            'remaining' => $activeItems->sum('estimated_price') - $totalActual,
            'completion_percentage' => $totalEstimated > 0 ? round(($totalActual / $totalEstimated) * 100, 2) : 0,
        ];
    }
}
