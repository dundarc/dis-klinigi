<?php

namespace App\Http\Controllers;

use App\Models\ServiceExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceExpenseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ServiceExpense::class, 'serviceExpense');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceExpense::query();

        // Filters
        if ($type = $request->input('service_type')) {
            $query->where('service_type', $type);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('invoice_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('invoice_date', '<=', $to);
        }

        $expenses = $query->latest('invoice_date')->paginate(15)->withQueryString();

        $serviceTypes = ServiceExpense::distinct('service_type')->pluck('service_type');

        return view('stock.services.index', [
            'expenses' => $expenses,
            'serviceTypes' => $serviceTypes,
            'filters' => $request->only(['service_type', 'status', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stock.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_provider' => 'nullable|string|max:255',
            'service_type' => 'required|string|max:100',
            'invoice_number' => 'nullable|string|max:120',
            'amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'status' => 'required|in:paid,pending,overdue',
            'notes' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('invoice_file')) {
            $validated['invoice_path'] = $request->file('invoice_file')->store('stock/services', 'public');
        }

        // Set payment_date if status is paid
        if ($validated['status'] === 'paid') {
            $validated['payment_date'] = $validated['payment_date'] ?? now();
        }

        ServiceExpense::create($validated);

        return redirect()->route('stock.services.index')->with('success', 'Hizmet gideri başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceExpense $serviceExpense)
    {
        return view('stock.services.show', [
            'expense' => $serviceExpense,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceExpense $serviceExpense)
    {
        return view('stock.services.edit', [
            'expense' => $serviceExpense,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceExpense $serviceExpense)
    {
        $validated = $request->validate([
            'service_provider' => 'nullable|string|max:255',
            'service_type' => 'required|string|max:100',
            'invoice_number' => 'nullable|string|max:120',
            'amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'status' => 'required|in:paid,pending,overdue',
            'notes' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('invoice_file')) {
            // Delete old file if exists
            if ($serviceExpense->invoice_path) {
                Storage::disk('public')->delete($serviceExpense->invoice_path);
            }
            $validated['invoice_path'] = $request->file('invoice_file')->store('stock/services', 'public');
        }

        $serviceExpense->update($validated);

        return redirect()->route('stock.services.show', $serviceExpense)->with('success', 'Hizmet gideri başarıyla güncellendi.');
    }

    /**
     * Add payment to service expense.
     */
    public function addPayment(Request $request, ServiceExpense $serviceExpense)
    {
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
        ]);

        $paymentHistory = $serviceExpense->payment_history ?? [];

        $payment = [
            'amount' => $validated['payment_amount'],
            'date' => $validated['payment_date'],
            'method' => $validated['payment_method'],
            'notes' => $validated['notes'] ?? null,
            'receipt_path' => null,
        ];

        if ($request->hasFile('receipt_file')) {
            $payment['receipt_path'] = $request->file('receipt_file')->store('stock/services/payments', 'public');
        }

        $paymentHistory[] = $payment;

        $totalPaid = collect($paymentHistory)->sum('amount');

        $updateData = [
            'payment_history' => $paymentHistory,
        ];

        // Update status based on total paid
        if ($totalPaid >= $serviceExpense->amount) {
            $updateData['status'] = 'paid';
            $updateData['payment_date'] = $validated['payment_date'];
        } elseif ($totalPaid > 0) {
            $updateData['status'] = 'pending'; // Partial payment
        }

        $serviceExpense->update($updateData);

        return redirect()->route('stock.services.show', $serviceExpense)->with('success', 'Ödeme başarıyla eklendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceExpense $serviceExpense)
    {
        // Delete invoice file if exists
        if ($serviceExpense->invoice_path) {
            Storage::disk('public')->delete($serviceExpense->invoice_path);
        }

        // Delete payment receipt files
        if ($serviceExpense->payment_history) {
            foreach ($serviceExpense->payment_history as $payment) {
                if (isset($payment['receipt_path'])) {
                    Storage::disk('public')->delete($payment['receipt_path']);
                }
            }
        }

        $serviceExpense->delete();

        return redirect()->route('stock.services.index')->with('success', 'Hizmet gideri başarıyla silindi.');
    }
}
