<?php

namespace Database\Seeders;

use App\Models\Stock\StockPurchaseInvoice;
use App\Models\Stock\StockPurchaseItem;
use App\Models\Stock\StockSupplier;
use App\Models\Stock\StockItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockPurchaseSeeder extends Seeder
{
    public function run(): void
    {
        // Get suppliers and items
        $suppliers = StockSupplier::all();
        $items = StockItem::all();
        $user = User::first(); // Get first user (admin)

        $purchaseInvoices = [
            [
                'supplier' => $suppliers->where('name', 'Medikal Tedarik A.Ş.')->first(),
                'invoice_number' => 'MT-2024-001',
                'invoice_date' => now()->subDays(30),
                'due_date' => now()->subDays(30)->addDays(30),
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'notes' => 'Aylık diş malzemeleri siparişi',
                'items' => [
                    ['item' => $items->where('name', 'Latex Eldiven (Orta Boy)')->first(), 'quantity' => 10, 'unit_price' => 5.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Cerrahi Maske')->first(), 'quantity' => 8, 'unit_price' => 2.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Steril Örtü (Mavi)')->first(), 'quantity' => 5, 'unit_price' => 15.00, 'vat_rate' => 18],
                ],
            ],
            [
                'supplier' => $suppliers->where('name', 'Diş Sağlık Malzemeleri Ltd.')->first(),
                'invoice_number' => 'DS-2024-001',
                'invoice_date' => now()->subDays(25),
                'due_date' => now()->subDays(25)->addDays(30),
                'payment_status' => 'paid',
                'payment_method' => 'credit_card',
                'notes' => 'Protez malzemeleri',
                'items' => [
                    ['item' => $items->where('name', 'Dental İmplant (4.1mm)')->first(), 'quantity' => 3, 'unit_price' => 500.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Abutment (Standart)')->first(), 'quantity' => 5, 'unit_price' => 200.00, 'vat_rate' => 18],
                ],
            ],
            [
                'supplier' => $suppliers->where('name', 'İzmir Medikal')->first(),
                'invoice_number' => 'IM-2024-001',
                'invoice_date' => now()->subDays(20),
                'due_date' => now()->subDays(20)->addDays(45),
                'payment_status' => 'partial',
                'payment_method' => 'bank_transfer',
                'notes' => 'Kompozit ve bonding malzemeleri',
                'items' => [
                    ['item' => $items->where('name', 'Kompozit Dolgu (A2 Shade)')->first(), 'quantity' => 4, 'unit_price' => 100.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Bonding Ajanı')->first(), 'quantity' => 6, 'unit_price' => 50.00, 'vat_rate' => 18],
                ],
            ],
            [
                'supplier' => $suppliers->where('name', 'Bursa Sağlık Ürünleri')->first(),
                'invoice_number' => 'BS-2024-001',
                'invoice_date' => now()->subDays(15),
                'due_date' => now()->subDays(15)->addDays(30),
                'payment_status' => 'pending',
                'payment_method' => 'cash',
                'notes' => 'Endodontik malzemeler',
                'items' => [
                    ['item' => $items->where('name', 'Kanal Dolgu Materyali (AH Plus)')->first(), 'quantity' => 15, 'unit_price' => 20.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Kanal Aleti Seti')->first(), 'quantity' => 12, 'unit_price' => 150.00, 'vat_rate' => 18],
                ],
            ],
            [
                'supplier' => $suppliers->where('name', 'Antalya Medikal Distribütör')->first(),
                'invoice_number' => 'AM-2024-001',
                'invoice_date' => now()->subDays(10),
                'due_date' => now()->subDays(10)->addDays(15),
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'notes' => 'Tüketim malzemeleri',
                'items' => [
                    ['item' => $items->where('name', 'El Dezenfektanı')->first(), 'quantity' => 20, 'unit_price' => 10.00, 'vat_rate' => 18],
                    ['item' => $items->where('name', 'Yüzey Temizleyici')->first(), 'quantity' => 15, 'unit_price' => 8.00, 'vat_rate' => 18],
                ],
            ],
            [
                'supplier' => $suppliers->where('name', 'Konya Diş Malzemeleri')->first(),
                'invoice_number' => 'KD-2024-001',
                'invoice_date' => now()->subDays(5),
                'due_date' => now()->subDays(5)->addDays(60),
                'payment_status' => 'pending',
                'payment_method' => 'bank_transfer',
                'notes' => 'El aletleri ve motor',
                'items' => [
                    ['item' => $items->where('name', 'Kürtaj Aleti Seti')->first(), 'quantity' => 6, 'unit_price' => 300.00, 'vat_rate' => 18],
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