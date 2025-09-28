<?php

namespace Database\Seeders;

use App\Models\Stock\StockPurchaseInvoice;
use App\Models\Stock\StockPurchaseItem;
use App\Models\Stock\StockSupplier;
use App\Models\Stock\StockItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockPurchaseInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        // Get suppliers and items
        $suppliers = StockSupplier::where('type', 'supplier')->get();
        $items = StockItem::all();
        $user = User::first(); // Get first user (admin)

        $purchaseInvoices = [
            [
                'supplier' => $suppliers->where('name', 'Dentsply Sirona')->first(),
                'invoice_number' => 'DS-2024-001',
                'invoice_date' => now()->subDays(30),
                'due_date' => now()->subDays(30)->addDays(30),
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'notes' => 'Aylık diş malzemeleri siparişi',
                'items' => [
                    ['item' => $items->where('name', 'Composite Resin A2')->first(), 'quantity' => 10, 'unit_price' => 450.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Composite Resin A3')->first(), 'quantity' => 8, 'unit_price' => 450.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Bonding Agent')->first(), 'quantity' => 5, 'unit_price' => 180.00, 'vat_rate' => 18],
                ],
            ],
            [
                'supplier' => $suppliers->where('name', 'Ivoclar Vivadent')->first(),
                'invoice_number' => 'IV-2024-001',
                'invoice_date' => now()->subDays(25),
                'due_date' => now()->subDays(25)->addDays(30),
                'payment_status' => 'paid',
                'payment_method' => 'credit_card',
                'notes' => 'Protez malzemeleri',
                'items' => [
                    ['item' => $items->where('name', 'Porcelain Veneer Kit')->first(), 'quantity' => 3, 'unit_price' => 1250.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Zirconia Crown Material')->first(), 'quantity' => 5, 'unit_price' => 890.00, 'vat_rate' => 18],
                ],
            ],
            [
                'supplier' => $suppliers->where('name', '3M ESPE')->first(),
                'invoice_number' => '3M-2024-001',
                'invoice_date' => now()->subDays(20),
                'due_date' => now()->subDays(20)->addDays(45),
                'payment_status' => 'partial',
                'payment_method' => 'bank_transfer',
                'notes' => 'Kompozit ve bonding malzemeleri',
                'items' => [
                    ['item' => $items->where('name', 'Light Cure Composite Kit')->first(), 'quantity' => 4, 'unit_price' => 680.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Bonding Agent')->first(), 'quantity' => 6, 'unit_price' => 175.00, 'vat_rate' => 18],
                ],
            ],
            [
                'supplier' => $suppliers->where('name', 'Medicadent')->first(),
                'invoice_number' => 'MC-2024-001',
                'invoice_date' => now()->subDays(15),
                'due_date' => now()->subDays(15)->addDays(30),
                'payment_status' => 'pending',
                'payment_method' => 'cash',
                'notes' => 'Endodontik malzemeler',
                'items' => [
                    ['item' => $items->where('name', 'Endodontic Files #15-40')->first(), 'quantity' => 15, 'unit_price' => 95.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Endodontic Files #45-80')->first(), 'quantity' => 12, 'unit_price' => 95.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Sodium Hypochlorite 5.25%')->first(), 'quantity' => 8, 'unit_price' => 45.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'EDTA Solution')->first(), 'quantity' => 6, 'unit_price' => 65.00, 'vat_rate' => 18],
                ],
            ],
            [
                'supplier' => $suppliers->where('name', 'DişDepo')->first(),
                'invoice_number' => 'DD-2024-001',
                'invoice_date' => now()->subDays(10),
                'due_date' => now()->subDays(10)->addDays(15),
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'notes' => 'Tüketim malzemeleri',
                'items' => [
                    ['item' => $items->where('name', 'Sterile Gloves Medium')->first(), 'quantity' => 20, 'unit_price' => 25.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Sterile Gloves Large')->first(), 'quantity' => 15, 'unit_price' => 25.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Face Masks')->first(), 'quantity' => 30, 'unit_price' => 8.50, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Dental Bibs')->first(), 'quantity' => 25, 'unit_price' => 12.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Dental Floss')->first(), 'quantity' => 40, 'unit_price' => 15.00, 'vat_rate' => 18],
                ],
            ],
            [
                'supplier' => $suppliers->where('name', 'NSK Dental')->first(),
                'invoice_number' => 'NSK-2024-001',
                'invoice_date' => now()->subDays(5),
                'due_date' => now()->subDays(5)->addDays(60),
                'payment_status' => 'pending',
                'payment_method' => 'bank_transfer',
                'notes' => 'El aletleri ve motor',
                'items' => [
                    ['item' => $items->where('name', 'Temporary Filling Material')->first(), 'quantity' => 6, 'unit_price' => 85.00, 'vat_rate' => 18],
                ],
            ],
        ];

        foreach ($purchaseInvoices as $invoiceData) {
            DB::transaction(function () use ($invoiceData, $user) {
                $supplier = $invoiceData['supplier'];

                // Calculate totals
                $subtotal = 0;
                $vatTotal = 0;

                foreach ($invoiceData['items'] as $itemData) {
                    $quantity = $itemData['quantity'];
                    $unitPrice = $itemData['unit_price'];
                    $vatRate = $itemData['vat_rate'];

                    $lineSubtotal = $quantity * $unitPrice;
                    $lineVat = $lineSubtotal * ($vatRate / 100);

                    $subtotal += $lineSubtotal;
                    $vatTotal += $lineVat;
                }

                $grandTotal = $subtotal + $vatTotal;

                // Create invoice
                $invoice = StockPurchaseInvoice::create([
                    'supplier_id' => $supplier->id,
                    'invoice_number' => $invoiceData['invoice_number'],
                    'invoice_date' => $invoiceData['invoice_date'],
                    'due_date' => $invoiceData['due_date'],
                    'payment_status' => $invoiceData['payment_status'],
                    'payment_method' => $invoiceData['payment_method'],
                    'subtotal' => $subtotal,
                    'vat_total' => $vatTotal,
                    'grand_total' => $grandTotal,
                    'notes' => $invoiceData['notes'],
                ]);

                // Create invoice items
                foreach ($invoiceData['items'] as $itemData) {
                    $quantity = $itemData['quantity'];
                    $unitPrice = $itemData['unit_price'];
                    $vatRate = $itemData['vat_rate'];

                    $lineSubtotal = $quantity * $unitPrice;
                    $lineVat = $lineSubtotal * ($vatRate / 100);
                    $lineTotal = $lineSubtotal + $lineVat;

                    StockPurchaseItem::create([
                        'purchase_invoice_id' => $invoice->id,
                        'stock_item_id' => $itemData['item']->id,
                        'description' => $itemData['item']->name,
                        'quantity' => $quantity,
                        'unit' => $itemData['item']->unit,
                        'unit_price' => $unitPrice,
                        'vat_rate' => $vatRate,
                        'line_total' => $lineTotal,
                    ]);

                    // Update stock quantity
                    $itemData['item']->increment('quantity', $quantity);
                }

                // Create account movement if supplier exists
                if ($supplier && in_array($invoiceData['payment_status'], ['paid', 'partial'])) {
                    $invoice->accountMovements()->create([
                        'supplier_id' => $supplier->id,
                        'direction' => 'debit',
                        'amount' => $grandTotal,
                        'movement_date' => $invoiceData['invoice_date'],
                        'payment_method' => $invoiceData['payment_method'],
                        'description' => 'Stok faturası - ' . $invoiceData['invoice_number'],
                    ]);
                }
            });
        }
    }
}