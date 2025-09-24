<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PatientTreatment;
use App\Http\Requests\Api\V1\StoreInvoiceRequest;
use App\Http\Requests\Api\V1\UpdateInvoiceRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvoiceController extends Controller
{
    use AuthorizesRequests;

    /**
     * YENÄ°: Tek bir faturanÄ±n detaylarÄ±nÄ±, iliÅŸkili kalemleriyle birlikte dÃ¶ndÃ¼rÃ¼r.
     * Bu metod, fatura dÃ¼zenleme modalÄ±nÄ±n verilerini doldurmak iÃ§in kullanÄ±lÄ±r.
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        return response()->json($invoice->load('items'));
    }

    /**
     * Yeni bir fatura oluÅŸturur.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $validated = $request->validated();
        
        $invoice = DB::transaction(function () use ($validated) {
            $treatments = PatientTreatment::whereIn('id', $validated['treatment_ids'])
                ->whereDoesntHave('invoiceItem')
                ->with('treatment')
                ->get();

            if ($treatments->isEmpty()) {
                abort(422, 'SeÃ§ilen tedaviler faturalandÄ±rmaya uygun deÄŸil.');
            }

            $subtotal = $treatments->sum('unit_price');
            $vatTotal = $treatments->sum(fn ($treatment) => $treatment->unit_price * ($treatment->vat / 100));

            $invoiceData = [
                'patient_id' => $validated['patient_id'],
                'invoice_no' => 'FAT-' . time(),
                'issue_date' => $validated['issue_date'],
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'discount_total' => 0,
                'grand_total' => $subtotal + $vatTotal,
            ];
            
            $invoice = Invoice::create($invoiceData);

            foreach ($treatments as $treatment) {
                $invoice->items()->create([
                    'patient_treatment_id' => $treatment->id,
                    'description' => $treatment->display_treatment_name,
                    'qty' => 1,
                    'unit_price' => $treatment->unit_price,
                    'vat' => $treatment->vat,
                    'line_total' => $treatment->unit_price,
                ]);
            }
            
            return $invoice;
        });

        return response()->json($invoice->load('items'), 201);
    }

    /**
     * Mevcut bir faturayÄ± gÃ¼nceller.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $validated = $request->validated();
        
        DB::transaction(function () use ($invoice, $validated) {
            $invoice->items()->delete();
            
            $subtotal = 0;
            $vatTotal = 0;

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['qty'] * $item['unit_price'];
                $subtotal += $lineTotal;
                $vatTotal += $lineTotal * ($item['vat'] / 100);

                $invoice->items()->create([
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'vat' => $item['vat'] ?? 20,
                    'line_total' => $lineTotal,
                ]);
            }
            
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

    /**
     * Bir faturayÄ± siler.
     */
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



