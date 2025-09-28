<?php

namespace App\Console\Commands;

use App\Models\PatientTreatment;
use Illuminate\Console\Command;

class FixTreatmentDisplayNames extends Command
{
    protected $signature = 'fix:treatment-display-names {--dry-run : Show what would be fixed without making changes}';
    protected $description = 'Fix patient treatments that have incorrect display names';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('=== Finding Patient Treatments with Display Name Issues ===');
        
        // Find patient treatments where the stored display name doesn't match the actual treatment name
        $problematicTreatments = PatientTreatment::with(['treatment', 'treatmentPlanItem.treatment'])
            ->whereNotNull('display_treatment_name')
            ->get()
            ->filter(function($pt) {
                $storedName = $pt->attributes['display_treatment_name'] ?? null;
                $actualName = $pt->treatment ? $pt->treatment->name : null;
                $planItemName = $pt->treatmentPlanItem && $pt->treatmentPlanItem->treatment ? $pt->treatmentPlanItem->treatment->name : null;
                
                // If stored name differs from actual treatment name, it might be wrong
                return $storedName && $actualName && $storedName !== $actualName;
            });
            
        if ($problematicTreatments->isEmpty()) {
            $this->info('No problematic patient treatments found.');
            return 0;
        }
        
        $this->info("Found {$problematicTreatments->count()} patient treatments with potential display name issues:");
        
        foreach ($problematicTreatments as $pt) {
            $storedName = $pt->attributes['display_treatment_name'] ?? 'NULL';
            $actualName = $pt->treatment ? $pt->treatment->name : 'NULL';
            $planItemName = $pt->treatmentPlanItem && $pt->treatmentPlanItem->treatment ? $pt->treatmentPlanItem->treatment->name : 'NULL';
            
            $this->warn("PT ID: {$pt->id}");
            $this->warn("  Patient: {$pt->patient_id}");
            $this->warn("  Treatment Plan Item ID: " . ($pt->treatment_plan_item_id ?? 'NULL'));
            $this->warn("  Stored Display Name: {$storedName}");
            $this->warn("  Actual Treatment Name: {$actualName}");
            $this->warn("  Plan Item Treatment Name: {$planItemName}");
            $this->warn("  Created: {$pt->created_at}");
            
            // Determine the correct name to use
            $correctName = null;
            if ($pt->treatmentPlanItem && $pt->treatmentPlanItem->treatment) {
                // If linked to treatment plan item, use that treatment's name
                $correctName = $pt->treatmentPlanItem->treatment->name;
                $source = 'treatment plan item';
            } elseif ($pt->treatment) {
                // Otherwise use the direct treatment relationship
                $correctName = $pt->treatment->name;
                $source = 'direct treatment relationship';
            }
            
            if ($correctName && $correctName !== $storedName) {
                $this->info("  → Should be: {$correctName} (from {$source})");
                
                if (!$isDryRun) {
                    $pt->update(['display_treatment_name' => $correctName]);
                    $this->info("  ✓ Fixed");
                } else {
                    $this->info("  ⚠ Would fix in non-dry-run mode");
                }
            } else {
                $this->info("  → No clear fix available");
            }
            
            $this->line('---');
        }
        
        if ($isDryRun) {
            $this->info("Dry run completed. Use --no-dry-run to apply fixes.");
        } else {
            $this->info("Fix completed.");
        }
        
        return 0;
    }
}