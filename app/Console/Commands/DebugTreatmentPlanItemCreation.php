<?php

namespace App\Console\Commands;

use App\Models\PatientTreatment;
use App\Models\TreatmentPlanItem;
use Illuminate\Console\Command;

class DebugTreatmentPlanItemCreation extends Command
{
    protected $signature = 'debug:treatment-plan-item {itemId}';
    protected $description = 'Debug treatment plan item and related patient treatments';

    public function handle()
    {
        $itemId = $this->argument('itemId');
        $item = TreatmentPlanItem::with(['treatment', 'treatmentPlan.patient'])->find($itemId);
        
        if (!$item) {
            $this->error("Treatment plan item with ID {$itemId} not found.");
            return 1;
        }

        $this->info("=== Treatment Plan Item Debug ===");
        $this->info("Item ID: {$item->id}");
        $this->info("Treatment ID: {$item->treatment_id}");
        $this->info("Treatment Name: " . ($item->treatment ? $item->treatment->name : 'DELETED'));
        $this->info("Patient: {$item->treatmentPlan->patient->first_name} {$item->treatmentPlan->patient->last_name}");
        $this->info("Tooth Number: " . ($item->tooth_number ?? 'N/A'));
        $this->info("Price: {$item->estimated_price}");
        $this->info("Status: {$item->status->value}");
        $this->info("Created: {$item->created_at}");
        $this->info("Updated: {$item->updated_at}");

        // Find related patient treatments
        $patientTreatments = PatientTreatment::where(function($query) use ($item) {
            $query->where('treatment_plan_item_id', $item->id)
                  ->orWhere(function($subQuery) use ($item) {
                      $subQuery->where('patient_id', $item->treatmentPlan->patient_id)
                               ->where('treatment_id', $item->treatment_id)
                               ->where('tooth_number', $item->tooth_number)
                               ->where('unit_price', $item->estimated_price);
                  });
        })->with(['treatment', 'dentist'])->orderBy('created_at')->get();

        $this->info("\n=== Related Patient Treatments ===");
        if ($patientTreatments->isEmpty()) {
            $this->warn("No related patient treatments found.");
        } else {
            foreach ($patientTreatments as $pt) {
                $this->info("PT ID: {$pt->id}");
                $this->info("  Treatment Plan Item ID: " . ($pt->treatment_plan_item_id ?? 'NULL'));
                $this->info("  Treatment ID: {$pt->treatment_id}");
                $this->info("  STORED Display Name: " . ($pt->attributes['display_treatment_name'] ?? 'NULL'));
                $this->info("  ACCESSOR Display Name: {$pt->display_treatment_name}");
                $this->info("  Actual Treatment Name: " . ($pt->treatment ? $pt->treatment->name : 'DELETED'));
                $this->info("  Tooth: " . ($pt->tooth_number ?? 'N/A'));
                $this->info("  Price: {$pt->unit_price}");
                $this->info("  Dentist: " . ($pt->dentist ? $pt->dentist->name : 'N/A'));
                $this->info("  Created: {$pt->created_at}");
                $this->info("  Notes: " . ($pt->notes ?? 'N/A'));
                $this->info("---");
            }
        }

        // Check if there are duplicates
        $duplicates = $patientTreatments->groupBy(function($pt) {
            return $pt->treatment_id . '-' . $pt->tooth_number . '-' . $pt->unit_price;
        })->filter(function($group) {
            return $group->count() > 1;
        });

        if (!$duplicates->isEmpty()) {
            $this->error("\n=== DUPLICATES DETECTED ===");
            foreach ($duplicates as $key => $group) {
                $this->error("Duplicate group: {$key}");
                foreach ($group as $pt) {
                    $this->error("  - PT ID: {$pt->id}, Created: {$pt->created_at}, Display: {$pt->display_treatment_name}");
                }
            }
        }
        
        // Check for treatment name mismatches
        $mismatches = $patientTreatments->filter(function($pt) {
            $storedName = $pt->attributes['display_treatment_name'] ?? null;
            $relationshipName = $pt->treatment ? $pt->treatment->name : null;
            return $storedName && $relationshipName && $storedName !== $relationshipName;
        });
        
        if (!$mismatches->isEmpty()) {
            $this->error("\n=== TREATMENT NAME MISMATCHES DETECTED ===");
            foreach ($mismatches as $pt) {
                $this->error("PT ID: {$pt->id}");
                $this->error("  Stored Name: " . ($pt->attributes['display_treatment_name'] ?? 'NULL'));
                $this->error("  Relationship Name: " . ($pt->treatment ? $pt->treatment->name : 'NULL'));
            }
        }

        return 0;
    }
}