<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PatientTreatment;
use App\Enums\InvoiceStatus;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\UpdateInvoiceDetailsRequest;
use App\Http\Requests\StoreInvoiceRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Ana muhasebe sayfasını özet verilerle birlikte gösterir.
     */
    public function index()
    {
        $this->authorize('accessAccountingFeatures');

        $paidInvoices = Invoice::where('status', InvoiceStatus::PAID)->get();
        $overdueInvoices = Invoice::where('status', InvoiceStatus::OVERDUE)
            ->orWhere(function ($query) {
                $query->where('due_date', '<', now()->startOfDay())
                      ->where('status', InvoiceStatus::POSTPONED);
            })->get();
        $installmentInvoices = Invoice::where('status', InvoiceStatus::INSTALLMENT)->get();
        $postponedInvoices = Invoice::where('status', InvoiceStatus::POSTPONED)
            ->where(function ($query) {
                $query->whereNull('due_date')
                      ->orWhere('due_date', '>=', now()->startOfDay());
            })->get();
        $allInvoices = Invoice::with('patient')->latest()->paginate(15);

        return view('accounting.index', compact(
            'paidInvoices',
            'overdueInvoices',
            'installmentInvoices',
            'postponedInvoices',
            'allInvoices'
        ));
    }

    /**
     * Yeni fatura oluşturma sürecinin ilk adımı olan tedavi seçim ekranını gösterir.
     */
    public function create()
    {
        $this->authorize('accessAccountingFeatures');
        $patientsWithTreatments = Patient::whereHas('treatments', function ($query) {
            $query->where('status', 'done')->whereDoesntHave('invoiceItem');
        })->with(['treatments' => function ($query) {
            $query->where('status', 'done')->whereDoesntHave('invoiceItem')->with(['treatment', 'dentist']);
        }])->get();
        return view('accounting.new', compact('patientsWithTreatments'));
    }

    /**
     * Seçilen tedavilerden bir fatura önizlemesi hazırlar ve gösterir.
     */
    public function prepare(Request $request)
    {
        $this->authorize('accessAccountingFeatures');
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'treatment_ids' => 'required|array|min:1',
            'treatment_ids.*' => 'exists:patient_treatments,id',
        ]);

        $patient = Patient::findOrFail($validated['patient_id']);
        $treatments = PatientTreatment::with('treatment')->whereIn('id', $validated['treatment_ids'])->get();

        $items = $treatments->map(function ($treatment) {
            return [
                'description' => $treatment->treatment->name,
                'qty' => 1,
                'unit_price' => $treatment->unit_price,
                'vat' => $treatment->vat,
                'patient_treatment_id' => $treatment->id,
            ];
        });

        return view('accounting.prepare', compact('patient', 'items'));
    }

    /**
     * Önizleme ekranından gelen son fatura verisini kaydeder.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $this->authorize('accessAccountingFeatures');
        $validated = $request->validated();
        
        $invoice = DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $vatTotal = 0;
            foreach ($validated['items'] as $item) {
                $lineTotal = $item['qty'] * $item['unit_price'];
                $subtotal += $lineTotal;
                $vatTotal += $lineTotal * (($item['vat'] ?? 20) / 100);
            }
            
            $invoice = Invoice::create([
                'patient_id' => $validated['patient_id'],
                'invoice_no' => 'FAT-' . time(),
                'issue_date' => now(),
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'discount_total' => 0, // DÜZELTME: Eksik olan alan eklendi.
                'grand_total' => $subtotal + $vatTotal,
            ]);

            foreach ($validated['items'] as $item) {
                $invoice->items()->create($item + ['line_total' => $item['qty'] * $item['unit_price']]);
            }
            return $invoice;
        });

        return redirect()->route('accounting.invoices.action', $invoice)->with('success', "Fatura başarıyla oluşturuldu.");
    }
    
    /**
     * Tek bir fatura için işlem sayfasını gösterir.
     */
    public function action(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');
        $invoice->load(['patient', 'items.patientTreatment.treatment']);
        return view('accounting.invoices.action', ['invoice' => $invoice, 'statuses' => InvoiceStatus::cases()]);
    }

    /**
     * Fatura bilgilerini günceller.
     */
    public function update(UpdateInvoiceDetailsRequest $request, Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');
        $validated = $request->validated();
        $updateData = [
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'],
            'paid_at' => $validated['paid_at'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'],
            'insurance_coverage_amount' => $validated['insurance_coverage_amount'] ?? 0,
        ];
        if ($validated['status'] === InvoiceStatus::INSTALLMENT->value) {
            $updateData['payment_details'] = ['taksit_sayisi' => $validated['taksit_sayisi'], 'ilk_odeme_gunu' => $validated['ilk_odeme_gunu']];
        } else {
            $updateData['payment_details'] = null;
        }
        $invoice->update($updateData);
        return redirect()->route('accounting.invoices.action', $invoice)->with('success', 'Fatura bilgileri başarıyla güncellendi.');
    }

    /**
     * Bir faturayı çöp kutusuna taşır (Soft Delete).
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');
        $invoice->delete();
        return redirect()->route('accounting.index')->with('success', 'Fatura başarıyla çöp kutusuna taşındı.');
    }

    /**
     * Silinmiş faturaların olduğu çöp kutusunu gösterir.
     */
    public function trash()
    {
        $this->authorize('accessAccountingFeatures');
        $trashedInvoices = Invoice::onlyTrashed()->with('patient')->latest('deleted_at')->paginate(15);
        return view('accounting.trash', compact('trashedInvoices'));
    }

    /**
     * Çöp kutusundaki bir faturayı geri yükler.
     */
    public function restore(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');
        $invoice->restore();
        return redirect()->route('accounting.trash')->with('success', 'Fatura başarıyla geri yüklendi.');
    }

    /**
     * Bir faturayı kalıcı olarak siler.
     */
    public function forceDelete(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');
        DB::transaction(function() use ($invoice) {
            $invoice->items()->delete();
            $invoice->payments()->delete();
            $invoice->forceDelete();
        });
        return redirect()->route('accounting.trash')->with('success', 'Fatura kalıcı olarak silindi.');
    }
}

