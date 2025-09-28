<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TreatmentPlan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TreatmentPlanController extends Controller
{
    /**
     * Display the specified treatment plan.
     */
    public function show(TreatmentPlan $treatmentPlan): JsonResponse
    {
        // Load patient relationship for basic info
        $treatmentPlan->load('patient:id,first_name,last_name');

        return response()->json($treatmentPlan);
    }
}
