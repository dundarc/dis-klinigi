<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Prescription;
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
}