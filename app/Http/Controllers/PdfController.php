<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Prescription;
use App\Models\Encounter;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function downloadInvoice(Invoice $invoice)
    {
        // TODO: Yetki kontrolü (Policy) eklenmeli
        // $this->authorize('view', $invoice);

        $invoice->load(['patient', 'items']);

        $data = [
            'invoice' => $invoice,
        ];

        // Blade view'ini PDF'e çevir
        $pdf = Pdf::loadView('pdf.invoice', $data);

        // PDF'i tarayıcıda göster veya indir
        // download() -> dosyayı indirir
        // stream() -> tarayıcıda açar
        return $pdf->stream('fatura-'.$invoice->invoice_no.'.pdf');
    }

    public function downloadPrescription(Prescription $prescription)
    {
        // TODO: Yetki kontrolü (Policy) eklenmeli

        $prescription->load(['patient', 'dentist']);

        $data = [
            'prescription' => $prescription
        ];

        $pdf = Pdf::loadView('pdf.prescription', $data);

        return $pdf->stream('recete-'.$prescription->id.'.pdf');
    }

    public function downloadEncounter(Encounter $encounter)
    {
        // TODO: Yetki kontrolü (Policy) eklenmeli
        // $this->authorize('view', $encounter);

        $encounter->load([
            'patient',
            'dentist',
            'treatments.treatment',
            'treatments.dentist',
            'prescriptions.dentist',
            'files.uploader',
        ]);

        // Separate treatments from treatment plan vs manually added
        $treatmentPlanTreatments = $encounter->treatments->where('treatment_plan_item_id', '!=', null);
        $manualTreatments = $encounter->treatments->where('treatment_plan_item_id', null);

        // Load clinic settings
        $settings = Setting::all()->pluck('value', 'key')->all();
        $clinicName = $settings['clinic_name'] ?? 'DİŞ HEKİMİ KLİNİĞİ';

        $data = [
            'encounter' => $encounter,
            'treatmentPlanTreatments' => $treatmentPlanTreatments,
            'manualTreatments' => $manualTreatments,
            'clinicName' => $clinicName,
        ];

        $pdf = Pdf::loadView('pdf.encounter', $data);

        return $pdf->stream('ziyaret-'.$encounter->id.'.pdf');
    }
}