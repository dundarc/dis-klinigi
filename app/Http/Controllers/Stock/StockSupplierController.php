<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockSupplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockSupplierController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('accessStockManagement');

        $query = StockSupplier::query()->orderBy('name');

        if ($search = $request->string('q')->toString()) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('tax_number', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        }

        if ($type = $request->string('type')->toString()) {
            $query->where('type', $type);
        }

        $suppliers = $query->paginate(15)->withQueryString();

        return view('stock.suppliers.index', [
            'suppliers' => $suppliers,
            'filters' => [
                'q' => $search,
                'type' => $type,
            ],
        ]);
    }

    public function create(): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $this->validateSupplier($request);
        StockSupplier::create($validated);

        return redirect()->route('stock.suppliers.index')->with('success', 'Cari/Tedarikci kaydi olusturuldu.');
    }

    public function edit(StockSupplier $supplier): View
    {
        $this->authorize('accessStockManagement');

        return view('stock.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, StockSupplier $supplier): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $this->validateSupplier($request);
        $supplier->update($validated);

        return redirect()->route('stock.suppliers.index')->with('success', 'Cari/Tedarikci guncellendi.');
    }

    public function destroy(StockSupplier $supplier): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        if ($supplier->purchaseInvoices()->exists() || $supplier->expenses()->exists() || $supplier->accountMovements()->exists()) {
            return redirect()->route('stock.suppliers.index')->with('error', 'Hareketi bulunan kayit silinemez.');
        }

        $supplier->delete();

        return redirect()->route('stock.suppliers.index')->with('success', 'Cari/Tedarikci silindi.');
    }

    protected function validateSupplier(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['supplier', 'service'])],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'tax_number' => ['nullable', 'string', 'max:64'],
            'tax_office' => ['nullable', 'string', 'max:128'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}

