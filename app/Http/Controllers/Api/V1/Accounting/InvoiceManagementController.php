<?php
namespace App\Http\Controllers\Api\V1\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Enums\InvoiceStatus;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. BU SATIRI EKLEYİN

class InvoiceManagementController extends Controller
{
    use AuthorizesRequests; // 2. BU SATIRI EKLEYİN

    public function updateStatus(Request $request, Invoice $invoice)
    {
        // Artık bu satır hata vermeyecek
        $this->authorize('update', $invoice);

        $validated = $request->validate([
            'status' => ['required', new Enum(InvoiceStatus::class)],
        ]);

        $invoice->update($validated);
        return response()->json(['message' => 'Fatura durumu güncellendi.', 'status' => $invoice->status->value]);
    }

    public function updateInsurance(Request $request, Invoice $invoice)
    {
        // Artık bu satır hata vermeyecek
        $this->authorize('update', $invoice);

        $validated = $request->validate([
            'insurance_coverage_amount' => 'required|numeric|min:0|max:'.$invoice->grand_total,
        ]);

        $invoice->update($validated);
        return response()->json(['message' => 'Sigorta bilgileri güncellendi.', 'invoice' => $invoice->fresh()]);
    }

    public function sendEmail(Request $request, Invoice $invoice)
    {
        if (empty($invoice->patient->email)) {
            return response()->json(['message' => 'Hastanın kayıtlı bir e-posta adresi bulunmamaktadır.'], 422);
        }

        Mail::to($invoice->patient->email)->queue(new InvoiceMail($invoice->load('patient', 'items')));
        
        return response()->json(['message' => 'Fatura e-postası gönderim kuyruğuna eklendi.']);
    }
}