<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Http\Requests\Api\V1\StoreInvoiceRequest;
use App\Http\Requests\Api\V1\UpdateInvoiceRequest; // Bunu ekleyeceğiz
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvoiceController extends Controller
{
    public function store(StoreInvoiceRequest $request)
    {
        $validated = $request->validated();
        
        $invoice = DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $vatTotal = 0;

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['qty'] * $item['unit_price'];
                $subtotal += $lineTotal;
                $vatTotal += $lineTotal * ($item['vat'] / 100);
            }

            $invoiceData = [
                'patient_id' => $validated['patient_id'],
                'invoice_no' => 'FAT-' . time(), // Basit bir fatura no
                'issue_date' => $validated['issue_date'],
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'discount_total' => 0, // Şimdilik indirim yok
                'grand_total' => $subtotal + $vatTotal,
            ];
            
            $invoice = Invoice::create($invoiceData);

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'patient_treatment_id' => $item['patient_treatment_id'] ?? null,
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'vat' => $item['vat'],
                    'line_total' => $item['qty'] * $item['unit_price'],
                ]);
            }
            
            return $invoice;
        });

        return response()->json($invoice->load('items'), 201);
    }
     
     /**
      * Summary of update
      * @param \App\Http\Requests\Api\V1\UpdateInvoiceRequest $request
      * @param \App\Models\Invoice $invoice
      * @return \Illuminate\Http\JsonResponse
      */
     public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $validated = $request->validated();
        
        DB::transaction(function () use ($invoice, $validated) {
            // Önce mevcut kalemleri silelim
            $invoice->items()->delete();
            
            $subtotal = 0;
            $vatTotal = 0;
            foreach ($validated['items'] as $item) {
                $lineTotal = $item['qty'] * $item['unit_price'];
                $subtotal += $lineTotal;
                $vatTotal += $lineTotal * ($item['vat'] / 100);
            }
            
            // Yeni kalemleri ekleyelim
            foreach ($validated['items'] as $item) {
                $invoice->items()->create($item + ['line_total' => $item['qty'] * $item['unit_price']]);
            }
            
            // Ana faturayı güncelleyelim
            $invoice->update([
                'patient_id' => $validated['patient_id'],
                'issue_date' => $validated['issue_date'],
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'grand_total' => $subtotal + $vatTotal,
            ]);
        });

        return response()->json($invoice->fresh()->load('items'));
    }

    // YENİ: Destroy metodu
    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);

        DB::transaction(function () use ($invoice) {
            $invoice->items()->delete();
            $invoice->payments()->delete();
            $invoice->delete();
        });

        return response()->noContent();
    }
}