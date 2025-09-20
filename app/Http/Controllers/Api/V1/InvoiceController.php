<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Http\Requests\Api\V1\StoreInvoiceRequest;
use Illuminate\Support\Facades\DB;

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
}