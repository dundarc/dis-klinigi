<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\TreatmentPlanItem;
use App\Enums\EncounterType;
use App\Enums\EncounterStatus;

class ClinicBackfillRelations extends Command
{
    protected $signature = 'clinic:backfill-relations {--dry-run : Only show what would be changed without saving}';
    protected $description = 'Fix missing relations between appointments, encounters, and treatment plan items.';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $this->info("🔍 Backfilling relations (Dry Run = " . ($dryRun ? 'YES' : 'NO') . ")");

        DB::beginTransaction();
        try {
            $this->fixAppointments($dryRun);
            $this->fixDoneItems($dryRun);
            $this->fixEmptyEncounters($dryRun);

            if ($dryRun) {
                DB::rollBack();
                $this->info("✅ Dry run complete. No changes saved.");
            } else {
                DB::commit();
                $this->info("✅ Backfill complete. Changes saved.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 1. Completed appointments without encounters → create encounter.
     */
    protected function fixAppointments(bool $dryRun)
    {
        $appointments = Appointment::where('status', \App\Enums\AppointmentStatus::COMPLETED)
            ->doesntHave('encounter')
            ->get();

        $this->info("➡️ Found {$appointments->count()} completed appointments without encounter.");

        foreach ($appointments as $appt) {
            $msg = "Creating encounter for appointment #{$appt->id} (patient {$appt->patient_id})";
            if ($dryRun) {
                $this->line("DRY: " . $msg);
            } else {
                Encounter::create([
                    'patient_id'     => $appt->patient_id,
                    'appointment_id' => $appt->id,
                    'dentist_id'     => $appt->dentist_id,
                    'type'           => EncounterType::SCHEDULED,
                    'status'         => EncounterStatus::DONE,
                    'arrived_at'     => $appt->start_at,
                    'started_at'     => $appt->start_at,
                    'ended_at'       => $appt->end_at ?? $appt->start_at->copy()->addMinutes(30),
                    'notes'          => 'Otomatik olarak randevu tamamlanmasından oluşturuldu.',
                ]);
                $this->line("✔ " . $msg);
            }
        }
    }

    /**
     * 2. Done treatment plan items without encounter link → link them.
     */
    protected function fixDoneItems(bool $dryRun)
    {
        $items = TreatmentPlanItem::where('status', \App\Enums\TreatmentPlanItemStatus::DONE)
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('encounter_treatment_plan_item as etpi')
                  ->whereColumn('etpi.treatment_plan_item_id', 'treatment_plan_items.id');
            })
            ->with('treatmentPlan')
            ->get();

        $this->info("➡️ Found {$items->count()} DONE treatment plan items without encounter link.");

        foreach ($items as $item) {
            $patientId = $item->treatmentPlan->patient_id ?? null;

            // En yakın completed appointment varsa onu kullan
            $appt = Appointment::where('patient_id', $patientId)
                ->where('status', \App\Enums\AppointmentStatus::COMPLETED)
                ->orderByDesc('start_at')
                ->first();

            $encounter = $appt?->encounter;

            if (!$encounter && $appt) {
                if (!$dryRun) {
                    $encounter = Encounter::create([
                        'patient_id'     => $patientId,
                        'appointment_id' => $appt->id,
                        'dentist_id'     => $appt->dentist_id,
                        'type'           => EncounterType::SCHEDULED,
                        'status'         => EncounterStatus::DONE,
                        'arrived_at'     => $appt->start_at,
                        'started_at'     => $appt->start_at,
                        'ended_at'       => $appt->end_at ?? $appt->start_at->copy()->addMinutes(30),
                        'notes'          => 'Otomatik olarak tedavi kalemi bağlantısından oluşturuldu.',
                    ]);
                }
            }

            // Eğer appointment yoksa ad hoc encounter oluştur
            if (!$encounter) {
                if (!$dryRun) {
                    $encounter = Encounter::create([
                        'patient_id'     => $patientId,
                        'appointment_id' => null,
                        'dentist_id'     => $item->treatmentPlan->dentist_id,
                        'type'           => EncounterType::WALK_IN,
                        'status'         => EncounterStatus::DONE,
                        'arrived_at'     => now(),
                        'started_at'     => now(),
                        'ended_at'       => now(),
                        'notes'          => 'Otomatik olarak tedavi kalemi bağlantısından oluşturuldu.',
                    ]);
                }
            }

            $msg = "Linking item #{$item->id} to encounter #" . ($encounter ? $encounter->id : 'new');
            if ($dryRun) {
                $this->line("DRY: " . $msg);
            } else {
                DB::table('encounter_treatment_plan_item')->insert([
                    'encounter_id'            => $encounter->id,
                    'treatment_plan_item_id'  => $item->id,
                    'price'                   => $item->estimated_price,
                    'notes'                   => 'Auto-linked by backfill',
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ]);
                $this->line("✔ " . $msg);
            }
        }
    }

    /**
     * 3. Encounters without any items → try to auto-link nearby DONE items.
     */
    protected function fixEmptyEncounters(bool $dryRun)
    {
        $encounters = Encounter::whereDoesntHave('treatmentPlanItems')->get();
        $this->info("➡️ Found {$encounters->count()} encounters without items.");

        foreach ($encounters as $e) {
            $date = Carbon::parse($e->started_at ?? $e->created_at);
            $from = (clone $date)->subDay();
            $to   = (clone $date)->addDay();

            $items = TreatmentPlanItem::where('status', \App\Enums\TreatmentPlanItemStatus::DONE)
                ->whereHas('treatmentPlan', fn ($q) => $q->where('patient_id', $e->patient_id))
                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                      ->from('encounter_treatment_plan_item as etpi')
                      ->whereColumn('etpi.treatment_plan_item_id', 'treatment_plan_items.id');
                })
                ->whereBetween('updated_at', [$from, $to])
                ->limit(5)
                ->get();

            if ($items->isEmpty()) {
                $this->line("Skipping encounter #{$e->id} (no matching items found).");
                continue;
            }

            foreach ($items as $item) {
                $msg = "Linking item #{$item->id} to encounter #{$e->id}";
                if ($dryRun) {
                    $this->line("DRY: " . $msg);
                } else {
                    DB::table('encounter_treatment_plan_item')->insert([
                        'encounter_id'            => $e->id,
                        'treatment_plan_item_id'  => $item->id,
                        'price'                   => $item->estimated_price,
                        'notes'                   => 'Auto-linked by backfill (empty encounter)',
                        'created_at'              => now(),
                        'updated_at'              => now(),
                    ]);
                    $this->line("✔ " . $msg);
                }
            }
        }
    }
}
