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
        // Güvenli eager loading - sadece mevcut ilişkileri yükle
        $plan->load([
            'patient:id,first_name,last_name,national_id,phone_primary',
            'dentist:id,name',
            'items' => function ($query) {
                $query->with([
                    'treatment:id,name',
                    'appointment:id,start_at,status,updated_at,dentist_id',
                    'appointment.dentist:id,name',
                    'appointmentHistory' => function ($q) {
                        $q->with(['user:id,name'])->orderBy('created_at', 'desc');
                    },
                    'histories' => function ($q) {
                        $q->with(['user:id,name'])->orderBy('created_at', 'desc');
                    }
                ]);
            }
        ]);

        // Cost summary hesapla
        $costSummary = app(TreatmentPlanService::class)->getCostSummary($plan);

        $data = [
            'plan' => $plan,
            'costSummary' => $costSummary,
        ];

        try {
            $pdf = Pdf::loadView('treatment_plans.pdf', $data);
            return $pdf->download('treatment-plan-' . $plan->id . '.pdf');
        } catch (\Exception $e) {
            // PDF oluşturma hatası durumunda logla ve hata döndür
            \Illuminate\Support\Facades\Log::error('PDF generation failed', [
                'plan_id' => $plan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \Exception('PDF oluşturma sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }
}