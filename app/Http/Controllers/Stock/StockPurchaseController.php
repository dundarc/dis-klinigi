<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockItem;
use App\Models\Stock\StockPurchaseInvoice;
use App\Models\Stock\StockPurchaseItem;
use App\Models\Stock\StockSupplier;
use App\Services\Stock\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockPurchaseController extends Controller
{
    public function __construct(private readonly StockMovementService $movementService)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('accessStockManagement');

        $query = StockPurchaseInvoice::with('supplier')->latest('invoice_date')->latest();

        if ($supplier = $request->string('supplier_id')->toInteger()) {
            $query->where('supplier_id', $supplier);
        }

        if ($status = $request->string('payment_status')->toString()) {
            $query->where('payment_status', $status);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('invoice_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('invoice_date', '<=', $to);
        }

        return view('stock.purchases.index', [
            'invoices' => $query->paginate(15)->withQueryString(),
            'suppliers' => StockSupplier::orderBy('name')->get(['id', 'name']),
            'filters' => $request->only(['supplier_id', 'payment_status', 'date_from', 'date_to']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.purchases.create', [
            'suppliers' => StockSupplier::orderBy('name')->get(),
            'items' => StockItem::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $mode = $request->input('mode', 'manual');

        $rules = [
            'supplier_id' => ['nullable', 'exists:stock_suppliers,id'],
            'invoice_number' => ['nullable', 'string', 'max:120'],
            'invoice_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'payment_status' => ['required', Rule::in(['pending', 'partial', 'paid', 'overdue'])],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'mode' => ['required', Rule::in(['manual', 'upload'])],
        ];

        if ($mode === 'upload') {
            $rules['file'] = ['required', 'file', 'mimes:pdf', 'max:20480'];
        } else {
            $rules['items'] = ['required', 'array', 'min:1'];
            $rules['items.*.description'] = ['required', 'string', 'max:255'];
            $rules['items.*.quantity'] = ['required', 'numeric', 'gt:0'];
            $rules['items.*.unit'] = ['nullable', 'string', 'max:32'];
            $rules['items.*.unit_price'] = ['required', 'numeric', 'gte:0'];
            $rules['items.*.vat_rate'] = ['nullable', 'numeric', 'gte:0'];
            $rules['items.*.stock_item_id'] = ['nullable', 'exists:stock_items,id'];
            $rules['items.*.create_item'] = ['nullable', 'boolean'];
        }

        $validated = $request->validate($rules);

        $invoice = $mode === 'upload'
            ? $this->storeUploadedInvoice($request, $validated)
            : $this->storeManualInvoice($request, $validated);

        return redirect()->route('stock.purchases.show', $invoice)->with('success', 'Stok faturasi kaydedildi.');
    }

    public function show(StockPurchaseInvoice $purchase): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.purchases.show', [
            'invoice' => $purchase->load(['supplier', 'items.stockItem']),
        ]);
    }

    public function edit(StockPurchaseInvoice $purchase): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.purchases.edit', [
            'invoice' => $purchase->load('items.stockItem'),
            'suppliers' => StockSupplier::orderBy('name')->get(),
            'items' => StockItem::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, StockPurchaseInvoice $purchase): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $request->validate([
            'supplier_id' => ['nullable', 'exists:stock_suppliers,id'],
            'invoice_number' => ['nullable', 'string', 'max:120'],
            'invoice_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'payment_status' => ['required', Rule::in(['pending', 'partial', 'paid', 'overdue'])],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $purchase->update($validated);

        return redirect()->route('stock.purchases.show', $purchase)->with('success', 'Fatura bilgileri guncellendi.');
    }

    public function destroy(StockPurchaseInvoice $purchase): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        DB::transaction(function () use ($purchase) {
            $purchase->load('items.stockItem');

            foreach ($purchase->items as $line) {
                $item = $line->stockItem;
                if ($item) {
                    $this->movementService->recordOutgoing($item, (float) $line->quantity, [
                        'reference_type' => StockPurchaseInvoice::class,
                        'reference_id' => $purchase->id,
                        'note' => 'Fatura silindigi icin stoktan dusuldu',
                        'created_by' => auth()->id(),
                    ]);
                }

                $line->delete();
            }

            $purchase->accountMovements()->delete();

            if ($purchase->file_path) {
                Storage::disk('public')->delete($purchase->file_path);
            }

            $purchase->delete();
        });

        return redirect()->route('stock.purchases.index')->with('success', 'Fatura silindi.');
    }

    protected function storeUploadedInvoice(Request $request, array $validated): StockPurchaseInvoice
    {
        return DB::transaction(function () use ($request, $validated) {
            $path = $request->file('file')->store('stock/invoices', 'public');

            $invoice = StockPurchaseInvoice::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'invoice_number' => $validated['invoice_number'] ?? null,
                'invoice_date' => $validated['invoice_date'] ?? now()->toDateString(),
                'due_date' => $validated['due_date'] ?? null,
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'] ?? null,
                'subtotal' => 0,
                'vat_total' => 0,
                'grand_total' => 0,
                'notes' => $validated['notes'] ?? null,
                'file_path' => $path,
                'parsed_payload' => null,
            ]);

            if ($validated['supplier_id'] ?? false) {
                $this->createAccountMovement($invoice, 0, $validated);
            }

            return $invoice;
        });
    }

    protected function storeManualInvoice(Request $request, array $validated): StockPurchaseInvoice
    {
        return DB::transaction(function () use ($request, $validated) {
            $lines = collect($validated['items'])->map(function (array $item) {
                $quantity = (float) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];
                $vatRate = isset($item['vat_rate']) ? (float) $item['vat_rate'] : 0;
                $lineSubtotal = $quantity * $unitPrice;
                $lineVat = $lineSubtotal * ($vatRate / 100);

                return [
                    'description' => $item['description'],
                    'quantity' => $quantity,
                    'unit' => $item['unit'] ?? 'adet',
                    'unit_price' => $unitPrice,
                    'vat_rate' => $vatRate,
                    'line_subtotal' => $lineSubtotal,
                    'line_vat' => $lineVat,
                    'stock_item_id' => $item['stock_item_id'] ?? null,
                    'create_item' => (bool) ($item['create_item'] ?? false),
                ];
            });

            $subtotal = $lines->sum('line_subtotal');
            $vatTotal = $lines->sum('line_vat');
            $grandTotal = $subtotal + $vatTotal;

            $invoice = StockPurchaseInvoice::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'invoice_number' => $validated['invoice_number'] ?? null,
                'invoice_date' => $validated['invoice_date'] ?? now()->toDateString(),
                'due_date' => $validated['due_date'] ?? null,
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'] ?? null,
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'grand_total' => $grandTotal,
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->syncAccountMovement($invoice, $grandTotal, $validated);

            foreach ($lines as $line) {
                $stockItem = $this->resolveStockItem($line);

                $purchaseItem = new StockPurchaseItem([
                    'description' => $line['description'],
                    'quantity' => $line['quantity'],
                    'unit' => $line['unit'],
                    'unit_price' => $line['unit_price'],
                    'vat_rate' => $line['vat_rate'],
                    'line_total' => $line['line_subtotal'] + $line['line_vat'],
                ]);

                if ($stockItem) {
                    $purchaseItem->stockItem()->associate($stockItem);
                }

                $invoice->items()->save($purchaseItem);

                if ($stockItem) {
                    $this->movementService->recordIncoming($stockItem, $line['quantity'], [
                        'reference_type' => StockPurchaseInvoice::class,
                        'reference_id' => $invoice->id,
                        'note' => 'Fatura girisi',
                        'created_by' => $request->user()->id,
                    ]);
                }
            }

            return $invoice;
        });
    }

    protected function resolveStockItem(array $line): ?StockItem
    {
        if (! empty($line['stock_item_id'])) {
            return StockItem::find($line['stock_item_id']);
        }

        if (! $line['create_item']) {
            return null;
        }

        return StockItem::firstOrCreate(
            ['name' => $line['description']],
            [
                'unit' => $line['unit'],
                'quantity' => 0,
                'minimum_quantity' => 0,
                'allow_negative' => false,
                'is_active' => true,
            ]
        );
    }

    protected function syncAccountMovement(StockPurchaseInvoice $invoice, float $grandTotal, array $validated): void
    {
        if (empty($validated['supplier_id'])) {
            return;
        }

        $invoice->accountMovements()->create([
            'supplier_id' => $validated['supplier_id'],
            'direction' => 'debit',
            'amount' => $grandTotal,
            'movement_date' => $validated['invoice_date'] ?? now()->toDateString(),
            'payment_method' => $validated['payment_method'] ?? null,
            'description' => 'Stok faturasi',
        ]);
    }

    protected function createAccountMovement(StockPurchaseInvoice $invoice, float $grandTotal, array $validated): void
    {
        if (empty($validated['supplier_id'])) {
            return;
        }

        $invoice->accountMovements()->create([
            'supplier_id' => $validated['supplier_id'],
            'direction' => 'debit',
            'amount' => $grandTotal,
            'movement_date' => $validated['invoice_date'] ?? now()->toDateString(),
            'payment_method' => $validated['payment_method'] ?? null,
            'description' => 'Stok faturasi (PDF yukleme)',
        ]);
    }
}
