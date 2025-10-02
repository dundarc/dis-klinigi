<?php

namespace App\Services;

use App\Enums\KvkkAuditAction;
use App\Models\Appointment;
use App\Models\File;
use App\Models\Invoice;
use App\Models\KvkkAuditLog;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KvkkDeletionService
{
    /**
     * Perform soft delete on patient and related records for KVKK compliance.
     *
     * @param Patient $patient
     * @param User $actor
     * @return bool
     */
    public static function softDelete(Patient $patient, User $actor): bool
    {
        return DB::transaction(function () use ($patient, $actor) {
            // Soft delete patient
            $patient->update(['deleted_via' => 'kvkk']);
            $patient->delete();

            // Soft delete related records with KVKK marking
            self::markRelatedRecordsForDeletion($patient);

            // Log the action
            KvkkAuditLog::create([
                'patient_id' => $patient->id,
                'user_id' => $actor->id,
                'action' => KvkkAuditAction::SOFT_DELETE,
                'ip_address' => request()->ip(),
                'meta' => [
                    'reason' => 'KVKK compliance - patient data deletion request',
                    'actor_name' => $actor->name,
                ],
            ]);

            return true;
        });
    }

    /**
     * Perform hard delete on patient and all related records (admin only).
     *
     * @param Patient $patient
     * @param User $actor
     * @return bool
     */
    public static function hardDelete(Patient $patient, User $actor): bool
    {
        return DB::transaction(function () use ($patient, $actor) {
            // Force delete all related records first
            self::forceDeleteRelatedRecords($patient);

            // Force delete patient
            $patient->forceDelete();

            // Log the action
            KvkkAuditLog::create([
                'patient_id' => $patient->id,
                'user_id' => $actor->id,
                'action' => KvkkAuditAction::HARD_DELETE,
                'ip_address' => request()->ip(),
                'meta' => [
                    'reason' => 'KVKK compliance - permanent data deletion',
                    'actor_name' => $actor->name,
                    'warning' => 'All patient data permanently deleted',
                ],
            ]);

            return true;
        });
    }

    /**
     * Restore soft deleted patient and related records (admin only).
     *
     * @param Patient $patient
     * @param User $actor
     * @return bool
     */
    public static function restore(Patient $patient, User $actor): bool
    {
        return DB::transaction(function () use ($patient, $actor) {
            // Restore patient
            $patient->restore();
            $patient->update(['deleted_via' => null]);

            // Restore related records
            self::restoreRelatedRecords($patient);

            // Log the action
            KvkkAuditLog::create([
                'patient_id' => $patient->id,
                'user_id' => $actor->id,
                'action' => KvkkAuditAction::RESTORE,
                'ip_address' => request()->ip(),
                'meta' => [
                    'reason' => 'KVKK compliance - data restoration',
                    'actor_name' => $actor->name,
                ],
            ]);

            return true;
        });
    }

    /**
     * Mark related records for KVKK deletion.
     *
     * @param Patient $patient
     * @return void
     */
    private static function markRelatedRecordsForDeletion(Patient $patient): void
    {
        // Appointments
        Appointment::where('patient_id', $patient->id)
            ->update(['deleted_via' => 'kvkk']);

        // Invoices
        Invoice::where('patient_id', $patient->id)
            ->update(['deleted_via' => 'kvkk']);

        // Treatment Plans
        TreatmentPlan::where('patient_id', $patient->id)
            ->update(['deleted_via' => 'kvkk']);

        // Files
        File::where('patient_id', $patient->id)
            ->update(['deleted_via' => 'kvkk']);
    }

    /**
     * Force delete all related records.
     *
     * @param Patient $patient
     * @return void
     */
    private static function forceDeleteRelatedRecords(Patient $patient): void
    {
        // Delete appointments
        Appointment::where('patient_id', $patient->id)->forceDelete();

        // Delete invoices and related items/payments
        $invoices = Invoice::where('patient_id', $patient->id)->withTrashed()->get();
        foreach ($invoices as $invoice) {
            $invoice->payments()->forceDelete();
            $invoice->items()->forceDelete();
            $invoice->forceDelete();
        }

        // Delete treatment plans and related items
        $treatmentPlans = TreatmentPlan::where('patient_id', $patient->id)->withTrashed()->get();
        foreach ($treatmentPlans as $plan) {
            $plan->items()->forceDelete();
            $plan->forceDelete();
        }

        // Delete files
        File::where('patient_id', $patient->id)->forceDelete();

        // Delete consents
        $patient->consents()->forceDelete();

        // Delete audit logs
        $patient->kvkkAuditLogs()->forceDelete();
    }

    /**
     * Restore related records from soft delete.
     *
     * @param Patient $patient
     * @return void
     */
    private static function restoreRelatedRecords(Patient $patient): void
    {
        // Restore appointments
        Appointment::where('patient_id', $patient->id)
            ->where('deleted_via', 'kvkk')
            ->restore()
            ->update(['deleted_via' => null]);

        // Restore invoices
        Invoice::where('patient_id', $patient->id)
            ->where('deleted_via', 'kvkk')
            ->restore()
            ->update(['deleted_via' => null]);

        // Restore treatment plans
        TreatmentPlan::where('patient_id', $patient->id)
            ->where('deleted_via', 'kvkk')
            ->restore()
            ->update(['deleted_via' => null]);

        // Restore files
        File::where('patient_id', $patient->id)
            ->where('deleted_via', 'kvkk')
            ->restore()
            ->update(['deleted_via' => null]);
    }
}
