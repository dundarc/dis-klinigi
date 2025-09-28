<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\TreatmentPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfExportService
{
    public function generateTreatmentPlanPdf(TreatmentPlan $plan): Response
    {
        $plan->load([
            'patient',
            'dentist',
            'items.treatment',
            'items.appointment.dentist',
            'items.appointmentHistory.appointment',
            'items.appointmentHistory.user',
            'items.histories.user',
            'items.encounters'
        ]);

        $costSummary = app(TreatmentPlanService::class)->getCostSummary($plan);

        $data = [
            'plan' => $plan,
            'costSummary' => $costSummary,
        ];

        $pdf = Pdf::loadView('treatment_plans.pdf', $data);

        return $pdf->download('treatment-plan-' . $plan->id . '.pdf');
    }
}