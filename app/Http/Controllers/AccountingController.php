<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Enums\InvoiceStatus;
use App\Http\Requests\UpdateInvoiceDetailsRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AccountingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Ana muhasebe sayfasını özet verilerle birlikte gösterir.
     */
    public function main()
    {
        // Bu sayfaya erişim zaten rota seviyesinde kontrol ediliyor.
        $recentInvoices = Invoice::whereHas('patient')->with('patient')->latest()->take(10)->get();

        $installmentInvoices = Invoice::whereHas('patient')->with('patient')
            ->where('status', InvoiceStatus::INSTALLMENT)
            ->get();

        $overdueInvoices = Invoice::whereHas('patient')->with('patient')
            ->where('due_date', '<', now())
            ->whereIn('status', [InvoiceStatus::UNPAID, InvoiceStatus::POSTPONED])
            ->get();

        return view('accounting.main', compact(
            'recentInvoices',
            'installmentInvoices',
            'overdueInvoices'
        ));
    }

    /**
     * Tek bir fatura için işlem sayfasını gösterir.
     */
    public function action(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');
        $invoice->load(['patient', 'items.patientTreatment.treatment']);
        
        return view('accounting.invoices.action', [
            'invoice' => $invoice,
            'statuses' => InvoiceStatus::cases(),
        ]);
    }

    /**
     * Fatura bilgilerini günceller.
     */
    public function update(UpdateInvoiceDetailsRequest $request, Invoice $invoice)
    {
        // DÜZELTME: Yetki kontrolü artık FormRequest içinde yapıldığı için bu satırı siliyoruz.
        // $this->authorize('accessAccountingFeatures');
        
        $validated = $request->validated();
        
        $updateData = [
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'],
            'insurance_coverage_amount' => $validated['insurance_coverage_amount'] ?? 0,
        ];

        if ($validated['status'] === InvoiceStatus::INSTALLMENT->value) {
            $updateData['payment_details'] = [
                'taksit_sayisi' => $validated['taksit_sayisi'],
                'ilk_odeme_gunu' => $validated['ilk_odeme_gunu'],
            ];
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
        return redirect()->route('accounting.main')->with('success', 'Fatura başarıyla çöp kutusuna taşındı.');
    }

    /**
     * Silinmiş faturaların olduğu çöp kutusunu gösterir.
     */
    public function trash()
    {
        $this->authorize('accessAccountingFeatures');
        $trashedInvoices = Invoice::onlyTrashed()->with('patient')->latest('deleted_at')->get();

        return view('accounting.trash', compact('trashedInvoices'));
    }

    /**
     * Silinmiş bir faturayı geri yükler.
     */
    public function restore($id)
    {
        $this->authorize('accessAccountingFeatures');
        $invoice = Invoice::onlyTrashed()->findOrFail($id);
        $invoice->restore();

        return redirect()->route('accounting.trash')->with('success', 'Fatura başarıyla geri yüklendi.');
    }

    /**
     * Bir faturayı kalıcı olarak siler.
     */
    public function forceDelete($id)
    {
        $this->authorize('accessAccountingFeatures');
        $invoice = Invoice::onlyTrashed()->findOrFail($id);

        $invoice->items()->delete();
        $invoice->payments()->delete();
        
        $invoice->forceDelete();

        return redirect()->route('accounting.trash')->with('success', 'Fatura kalıcı olarak silindi.');
    }
}

