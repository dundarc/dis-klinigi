<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PatientTreatment;
use App\Models\Treatment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add display_treatment_name column if it doesn't exist
        if (!Schema::hasColumn('patient_treatments', 'display_treatment_name')) {
            Schema::table('patient_treatments', function (Blueprint $table) {
                $table->string('display_treatment_name')->nullable()->after('notes');
            });
        }
        
        // Backfill existing patient treatments with display names
        $patientTreatments = PatientTreatment::with('treatment')
            ->whereNull('display_treatment_name')
            ->orWhere('display_treatment_name', '')
            ->get();
            
        foreach ($patientTreatments as $patientTreatment) {
            if ($patientTreatment->treatment) {
                $patientTreatment->update([
                    'display_treatment_name' => $patientTreatment->treatment->name
                ]);
            } else {
                // Handle cases where treatment is deleted
                $patientTreatment->update([
                    'display_treatment_name' => PatientTreatment::DELETED_TREATMENT_LABEL
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't remove the column in down() as it might contain important data
        // Schema::table('patient_treatments', function (Blueprint $table) {
        //     $table->dropColumn('display_treatment_name');
        // });
    }
};
