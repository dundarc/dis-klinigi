<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Enums\InvoiceStatus;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        // Bu sayfaya sadece yetkili kişilerin erişebildiğinden emin olalım
        $this->authorize('accessAccountingFeatures', 'App\Models\Invoice');

        $query = Invoice::with('patient')->latest('issue_date');

        // Arama filtresi
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($patientQuery) use ($search) {
                      $patientQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

         $invoices = $query->paginate(20)->withQueryString();
        $patients = Patient::select('id', 'first_name', 'last_name')->orderBy('first_name')->get();

        return view('accounting.invoices.index', [
            'invoices' => $invoices,
            'statuses' => InvoiceStatus::cases(),
            'patients' => $patients, // HASTA LİSTESİNİ EKLE
        ]);

        // Durum filtresi
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $invoices = $query->paginate(20)->withQueryString();

        return view('accounting.invoices.index', [
            'invoices' => $invoices,
            'statuses' => InvoiceStatus::cases(), // Filtre dropdown'ı için
        ]);
    }
     public function show(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures', $invoice);

        $invoice->load(['patient', 'items.patientTreatment.treatment']);

        return view('accounting.invoices.show', [
            'invoice' => $invoice,
            'statuses' => InvoiceStatus::cases(),
        ]);
    }
}