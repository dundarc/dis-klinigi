<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\PatientTreatment;
use App\Models\Payment;
use App\Enums\InvoiceStatus;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\UpdateInvoiceDetailsRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\AddInvoiceItemRequest;
use App\Http\Requests\UpdateInvoiceItemRequest;
use App\Exceptions\InstallmentPlanException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the main accounting dashboard with summary statistics.
     *
     * This method retrieves and displays various invoice statistics including
     * paid, overdue, installment, and postponed invoices with eager loading
     * for better performance.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('accessAccountingFeatures');

        $paidInvoices = Invoice::with(['patient', 'items', 'payments'])->where('status', InvoiceStatus::PAID)->paginate(20);
        $overdueInvoices = Invoice::with(['patient', 'items', 'payments'])->where('status', InvoiceStatus::OVERDUE)
            ->orWhere(function ($query) {
                $query->where('due_date', '<', now()->startOfDay())
                      ->where('status', InvoiceStatus::POSTPONED);
            })->paginate(20);
        $installmentInvoices = Invoice::with(['patient', 'items', 'payments'])->where('status', InvoiceStatus::INSTALLMENT)->paginate(20);
        $postponedInvoices = Invoice::with(['patient', 'items', 'payments'])->where('status', InvoiceStatus::POSTPONED)
            ->where(function ($query) {
                $query->whereNull('due_date')
                      ->orWhere('due_date', '>=', now()->startOfDay());
            })->paginate(20);
        $allInvoices = Invoice::with(['patient', 'items', 'payments'])->latest()->paginate(20);

        return view('accounting.index', compact(
            'paidInvoices',
            'overdueInvoices',
            'installmentInvoices',
            'postponedInvoices',
            'allInvoices'
        ));
    }

    public function search(Request $request)
    {
        $this->authorize('accessAccountingFeatures');

        $query = Invoice::with('patient')->latest('issue_date');

        $patientId = $request->input('patient_id');
        if ($patientId) {
            $query->where('patient_id', $patientId);
        }

        $status = $request->input('status');
        if ($status) {
            $query->where('status', $status);
        }

        $dateFrom = $request->input('date_from');
        if ($dateFrom) {
            $query->whereDate('issue_date', '>=', $dateFrom);
        }

        $dateTo = $request->input('date_to');
        if ($dateTo) {
            $query->whereDate('issue_date', '<=', $dateTo);
        }

        $invoices = $query->paginate(15)->withQueryString();

        $patients = Patient::orderBy('first_name')->orderBy('last_name')->get(['id', 'first_name', 'last_name']);
        $statuses = InvoiceStatus::cases();

        return view('accounting.search', [
            'invoices' => $invoices,
            'patients' => $patients,
            'statuses' => $statuses,
            'filters' => [
                'patient_id' => $patientId,
                'status' => $status,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ]);
    }

    /**
     * Yeni fatura oluÅŸturma sÃ¼recinin ilk adÄ±mÄ± olan tedavi seÃ§im ekranÄ±nÄ± gÃ¶sterir.
     */
    public function create()
    {
        $this->authorize('accessAccountingFeatures');
        $patientsWithTreatments = Patient::whereHas('treatments', function ($query) {
            $query->where('status', 'done')->whereDoesntHave('invoiceItem');
        })->with(['treatments' => function ($query) {
            $query->where('status', 'done')->whereDoesntHave('invoiceItem')->with(['treatment', 'dentist']);
        }])->get();
        return view('accounting.new', compact('patientsWithTreatments'));
    }

    /**
     * Seçilen tedavilerden bir fatura önizlemesi hazırlar ve gösterir.
     */
    public function prepare(Request $request)
    {
        $this->authorize('accessAccountingFeatures');

        // Handle GET request - redirect to new invoice page if no parameters
        if ($request->isMethod('get')) {
            $patientId = $request->query('patient_id');
            $treatmentIds = $request->query('treatment_ids');

            if (!$patientId || !$treatmentIds) {
                return redirect()->route('accounting.new')
                    ->with('info', 'Fatura hazırlamak için önce hasta ve tedavileri seçin.');
            }

            // Validate query parameters
            $request->merge([
                'patient_id' => $patientId,
                'treatment_ids' => is_array($treatmentIds) ? $treatmentIds : explode(',', $treatmentIds),
            ]);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'treatment_ids' => 'required|array|min:1',
            'treatment_ids.*' => 'exists:patient_treatments,id,patient_id,' . $request->input('patient_id'),
        ]);

        $patient = Patient::findOrFail($validated['patient_id']);
        $treatments = PatientTreatment::with('treatment')->whereIn('id', $validated['treatment_ids'])->get();

        $items = $treatments->map(function ($treatment) {
            return [
                'description' => $treatment->display_treatment_name,
                'quantity' => 1,
                'unit_price' => $treatment->unit_price,
                'vat_rate' => $treatment->vat,
                'patient_treatment_id' => $treatment->id,
            ];
        });

        return view('accounting.prepare', compact('patient', 'items'));
    }

    /**
     * Store a newly created invoice from the preparation form.
     *
     * This method creates an invoice with its associated items in a database
     * transaction. It calculates subtotal, VAT, and grand total automatically.
     *
     * @param \App\Http\Requests\StoreInvoiceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreInvoiceRequest $request)
    {
        $this->authorize('accessAccountingFeatures');
        $validated = $request->validated();

        try {
            $invoice = DB::transaction(function () use ($validated) {
                $subtotal = 0;
                $vatTotal = 0;
                foreach ($validated['items'] as $item) {
                    $lineTotal = $item['quantity'] * $item['unit_price'];
                    $subtotal += $lineTotal;
                    $vatTotal += $lineTotal * (($item['vat_rate'] ?? config('accounting.vat_rate') * 100) / 100);
                }

                $invoice = Invoice::create([
                    'patient_id' => $validated['patient_id'],
                    'invoice_no' => 'FAT-' . time(),
                    'issue_date' => now(),
                    'status' => InvoiceStatus::from($validated['status']),
                    'due_date' => $validated['status'] === InvoiceStatus::POSTPONED->value ? $validated['due_date'] : null,
                    'subtotal' => $subtotal,
                    'vat_total' => $vatTotal,
                    'discount_total' => 0, // DÃœZELTME: Eksik olan alan eklendi.
                    'grand_total' => $subtotal + $vatTotal,
                ]);

                foreach ($validated['items'] as $item) {
                    $invoice->items()->create($item + ['line_total' => $item['quantity'] * $item['unit_price']]);
                }
                return $invoice;
            });

            return redirect()->route('accounting.invoices.show', $invoice)->with('success', "Fatura baÅŸarÄ±yla oluÅŸturuldu.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'İşlem sırasında bir hata oluştu. Lütfen tekrar deneyin.');
        }
    }

    /**
     * Tek bir faturayÄ± salt okunur ÅŸekilde gÃ¶sterir.
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');

        $invoice->load([
            'patient',
            'items.patientTreatment.treatment',
            'payments' => fn ($query) => $query->orderBy('paid_at'),
        ]);

        return view('accounting.invoices.show', compact('invoice'));
    }

    /**
     * Tek bir fatura iÃ§in iÅŸlem sayfasÄ±nÄ± gÃ¶sterir.
     */
    public function action(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');

        $invoice->load([
            'patient',
            'items.patientTreatment.treatment',
            'payments' => fn ($query) => $query->orderBy('paid_at'),
        ]);

        $paymentDetails = $invoice->payment_details ?? [];
        $partialDetails = $paymentDetails['partial'] ?? null;
        $installmentPlan = collect($paymentDetails['installments'] ?? [])
            ->map(function ($installment, $index) {
                $installment['sequence'] = $installment['sequence'] ?? $index + 1;
                return $installment;
            })
            ->all();

        $statuses = InvoiceStatus::cases();
        $vatOptions = [0, 1, 8, 10, 18, 20];
        $totalPaid = $invoice->payments->sum('amount');
        $outstandingBalance = max($invoice->patient_payable_amount - $totalPaid, 0);
        $patients = Patient::orderBy('first_name')->orderBy('last_name')->get(['id', 'first_name', 'last_name']);

        return view('accounting.invoices.action', [
            'invoice' => $invoice,
            'statuses' => $statuses,
            'vatOptions' => $vatOptions,
            'partialDetails' => $partialDetails,
            'installmentPlan' => $installmentPlan,
            'totalPaid' => $totalPaid,
            'outstandingBalance' => $outstandingBalance,
            'patients' => $patients,
        ]);
    }

    public function addItem(AddInvoiceItemRequest $request, Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');

        $data = $request->validated();

        $lineTotal = $data['quantity'] * $data['unit_price'];

        $invoice->items()->create([
            'description' => $data['description'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'vat_rate' => $data['vat_rate'],
            'line_total' => $lineTotal,
            'patient_treatment_id' => $data['patient_treatment_id'] ?? null,
        ]);

        $this->recalculateInvoiceTotals($invoice);

        return redirect()->route('accounting.invoices.action', $invoice)->with('success', 'Fatura kalemi eklendi.');
    }

    public function updateItem(UpdateInvoiceItemRequest $request, Invoice $invoice, InvoiceItem $item)
    {
        $this->authorize('accessAccountingFeatures');

        if ($item->invoice_id !== $invoice->id) {
            abort(404);
        }

        $data = $request->validated();

        $lineTotal = $data['quantity'] * $data['unit_price'];

        $item->update([
            'description' => $data['description'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'vat_rate' => $data['vat_rate'],
            'line_total' => $lineTotal,
        ]);

        $this->recalculateInvoiceTotals($invoice);

        return redirect()->route('accounting.invoices.action', $invoice)->with('success', 'Fatura kalemi güncellendi.');
    }

    public function destroyItem(Invoice $invoice, InvoiceItem $item)
    {
        $this->authorize('accessAccountingFeatures');

        if ($item->invoice_id !== $invoice->id) {
            abort(404);
        }

        $item->delete();
        $this->recalculateInvoiceTotals($invoice);

        return redirect()->route('accounting.invoices.action', $invoice)->with('success', 'Fatura kalemi silindi.');
    }

    /**
     * Update the specified invoice with payment and status information.
     *
     * This method handles various payment scenarios including full payment,
     * partial payments, and installment plans. It updates payment details
     * and manages related payment records.
     *
     * @param \App\Http\Requests\UpdateInvoiceDetailsRequest $request
     * @param \App\Models\Invoice $invoice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateInvoiceDetailsRequest $request, Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');

        try {
            $validated = $request->validated();
            $status = $validated['status'];

            $insuranceCoverage = max(0, $validated['insurance_coverage_amount'] ?? 0);
            if ($insuranceCoverage > $invoice->grand_total) {
                $insuranceCoverage = $invoice->grand_total;
            }

            $updateData = [
                'patient_id' => $validated['patient_id'],
                'issue_date' => $validated['issue_date'],
                'status' => $status,
                'notes' => $validated['notes'] ?? null,
                'insurance_coverage_amount' => $insuranceCoverage,
                'payment_method' => null,
                'paid_at' => null,
                'due_date' => null,
            ];

            $paymentDetails = $invoice->payment_details ?? [];
            unset($paymentDetails['installment_meta']);

            if ($status === InvoiceStatus::PAID->value) {
                $updateData['payment_method'] = $validated['payment_method'];
                $updateData['paid_at'] = $validated['paid_at'] ?? now();
                $paymentDetails['partial'] = null;
                $paymentDetails['installments'] = null;
            } elseif ($status === InvoiceStatus::POSTPONED->value) {
                $updateData['due_date'] = $validated['due_date'];
                $paymentDetails['installments'] = null;
            } elseif ($status === InvoiceStatus::PARTIAL->value) {
                $partialAmount = isset($validated['partial_payment_amount']) ? round($validated['partial_payment_amount'], 2) : null;
                $partialMethod = $validated['partial_payment_method'] ?? null;
                $partialDateInput = $validated['partial_payment_date'] ?? now()->format('Y-m-d');

                $totalPaid = $invoice->payments()->sum('amount');
                if ($partialAmount !== null && $partialAmount > 0) {
                    $remainingBefore = max($invoice->patient_payable_amount - $totalPaid, 0);
                    $partialAmount = min($partialAmount, $remainingBefore);

                    if ($partialAmount > 0) {
                        $invoice->payments()->create([
                            'amount' => $partialAmount,
                            'method' => $partialMethod,
                            'paid_at' => Carbon::parse($partialDateInput),
                        ]);
                        $totalPaid += $partialAmount;
                    }
                }

                $paymentDetails['partial'] = [
                    'last_payment' => [
                        'amount' => $partialAmount,
                        'method' => $partialMethod,
                        'paid_at' => $partialDateInput,
                    ],
                    'total_paid' => round($totalPaid, 2),
                    'remaining' => max(round($invoice->patient_payable_amount - $totalPaid, 2), 0),
                ];
                $updateData['payment_method'] = $partialMethod;
                $paymentDetails['installments'] = null;
            } elseif ($status === InvoiceStatus::INSTALLMENT->value) {
                $installmentCount = (int) $validated['taksit_sayisi'];
                $firstDueDate = $validated['ilk_odeme_gunu'];
                $plan = $this->generateInstallmentPlan($invoice, $installmentCount, $firstDueDate);
                $paymentDetails['installments'] = $plan;
                $paymentDetails['installment_meta'] = [
                    'count' => $installmentCount,
                    'first_due_date' => $firstDueDate,
                ];
                $lastInstallment = end($plan) ?: null;
                $updateData['due_date'] = $lastInstallment['due_date'] ?? $firstDueDate;
                $paymentDetails['partial'] = null;
            } else {
                $paymentDetails['partial'] = null;
                $paymentDetails['installments'] = null;
            }

            $cleanedDetails = [];
            foreach ($paymentDetails as $key => $value) {
                if ($value !== null) {
                    $cleanedDetails[$key] = $value;
                }
            }

            $updateData['payment_details'] = !empty($cleanedDetails) ? $cleanedDetails : null;

            $invoice->update($updateData);

            return redirect()->route('accounting.invoices.show', $invoice)->with('success', 'Fatura bilgileri basariyla guncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'İşlem sırasında bir hata oluştu. Lütfen tekrar deneyin.');
        }
    }


    /**
     * Bir faturayÄ± Ã§Ã¶p kutusuna taÅŸÄ±r (Soft Delete).
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');
        $invoice->delete();
        return redirect()->route('accounting.index')->with('success', 'Fatura baÅŸarÄ±yla Ã§Ã¶p kutusuna taÅŸÄ±ndÄ±.');
    }

    /**
     * SilinmiÅŸ faturalarÄ±n olduÄŸu Ã§Ã¶p kutusunu gÃ¶sterir.
     */
    public function trash()
    {
        $this->authorize('accessAccountingFeatures');
        $trashedInvoices = Invoice::onlyTrashed()->with('patient')->latest('deleted_at')->paginate(10);
        return view('accounting.trash', compact('trashedInvoices'));
    }

    /**
     * Ã‡Ã¶p kutusundaki bir faturayÄ± geri yÃ¼kler.
     */
    public function restore(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');
        $invoice->restore();
        return redirect()->route('accounting.trash')->with('success', 'Fatura baÅŸarÄ±yla geri yÃ¼klendi.');
    }

    /**
     * Ã‡Ã¶p kutusundaki birden fazla faturayÄ± toplu geri yÃ¼kler.
     */
    public function bulkRestore(Request $request)
    {
        $this->authorize('accessAccountingFeatures');

        $validated = $request->validate([
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'exists:invoices,id',
        ]);

        $count = Invoice::withTrashed()
            ->whereIn('id', $validated['invoice_ids'])
            ->restore();

        return redirect()->route('accounting.trash')->with('success', $count . ' fatura baÅŸarÄ±yla geri yÃ¼klendi.');
    }

    /**
     * Ã‡Ã¶p kutusundaki birden fazla faturayÄ± toplu kalÄ±cÄ± olarak siler.
     */
    public function bulkForceDelete(Request $request)
    {
        $this->authorize('accessAccountingFeatures');

        $validated = $request->validate([
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'exists:invoices,id',
        ]);

        $invoices = Invoice::withTrashed()
            ->whereIn('id', $validated['invoice_ids'])
            ->get();

        $deletableInvoices = [];
        $nonDeletableCount = 0;

        foreach ($invoices as $invoice) {
            $hasItems = $invoice->items()->exists();
            $hasPayments = $invoice->payments()->exists();

            if (!$hasItems && !$hasPayments) {
                $deletableInvoices[] = $invoice;
            } else {
                $nonDeletableCount++;
            }
        }

        if (empty($deletableInvoices)) {
            return redirect()->back()->with('error', 'Seçilen faturaların hiçbiri silinemez çünkü ilişkili kayıtları bulunmaktadır.');
        }

        try {
            $count = 0;
            foreach ($deletableInvoices as $invoice) {
                DB::transaction(function() use ($invoice) {
                    $invoice->items()->delete();
                    $invoice->payments()->delete();
                    $invoice->forceDelete();
                });
                $count++;
            }

            $message = $count . ' fatura kalıcı olarak silindi.';
            if ($nonDeletableCount > 0) {
                $message .= ' ' . $nonDeletableCount . ' fatura ilişkili kayıtları nedeniyle silinemedi.';
            }

            return redirect()->route('accounting.trash')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Toplu silme işlemi sırasında bir hata oluştu.');
        }
    }

    /**
     * Bir faturayÄ± kalÄ±cÄ± olarak siler.
     */
    public function forceDelete(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');

        // Check for related records
        $hasItems = $invoice->items()->exists();
        $hasPayments = $invoice->payments()->exists();

        if ($hasItems || $hasPayments) {
            return redirect()->back()->with('error', 'Bu fatura silinemez çünkü ilişkili kayıtları bulunmaktadır.');
        }

        try {
            DB::transaction(function() use ($invoice) {
                $invoice->items()->delete();
                $invoice->payments()->delete();
                $invoice->forceDelete();
            });
            return redirect()->route('accounting.trash')->with('success', 'Fatura kalıcı olarak silindi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Silme işlemi sırasında bir hata oluştu.');
        }
    }
    public function updateOverdueInvoices()
    {
        $this->authorize('accessAccountingFeatures');

        $today = now()->startOfDay();
        $updatedCount = 0;

        $installmentInvoices = Invoice::where('status', InvoiceStatus::INSTALLMENT)->get();
        foreach ($installmentInvoices as $invoice) {
            $paymentDetails = $invoice->payment_details ?? [];
            $plan = $paymentDetails['installments'] ?? [];
            $planChanged = false;

            foreach ($plan as $index => $row) {
                $dueDate = isset($row['due_date']) ? Carbon::parse($row['due_date'])->startOfDay() : null;
                $rowStatus = $row['status'] ?? 'pending';

                if ($dueDate && $dueDate->lt($today) && $rowStatus !== 'paid' && $rowStatus !== 'overdue') {
                    $plan[$index]['status'] = 'overdue';
                    $planChanged = true;
                }
            }

            $paymentDetails['installments'] = $plan;

            $totalPaid = $invoice->payments()->sum('amount');
            $outstanding = max($invoice->patient_payable_amount - $totalPaid, 0);
            $hasOverdueInstallment = collect($plan)->contains(fn ($row) => ($row['status'] ?? '') === 'overdue');

            $statusChanged = false;
            if ($hasOverdueInstallment && $outstanding > 0 && $invoice->status !== InvoiceStatus::OVERDUE) {
                $invoice->status = InvoiceStatus::OVERDUE;
                $statusChanged = true;
            }

            if ($planChanged || $statusChanged) {
                $invoice->payment_details = array_filter($paymentDetails, fn ($value) => $value !== null && $value !== []);
                $invoice->save();
                $updatedCount++;
            }
        }

        $postponedInvoices = Invoice::where('status', InvoiceStatus::POSTPONED)
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today)
            ->get();

        foreach ($postponedInvoices as $invoice) {
            if ($invoice->status !== InvoiceStatus::OVERDUE) {
                $invoice->status = InvoiceStatus::OVERDUE;
                $invoice->save();
                $updatedCount++;
            }
        }

        $message = $updatedCount
            ? $updatedCount . ' fatura guncellendi.'
            : 'Guncellenecek fatura bulunamadi.';

        return redirect()->route('accounting.index')->with('success', $message);
    }

    /**
     * Recalculate and update the invoice totals based on its items.
     *
     * This method recalculates subtotal, VAT total, and grand total
     * from the current invoice items and updates the invoice record.
     *
     * @param \App\Models\Invoice $invoice
     * @return void
     */
    protected function recalculateInvoiceTotals(Invoice $invoice): void
    {
        $invoice->loadMissing('items');

        $subtotal = $invoice->items->sum(fn (InvoiceItem $item) => $item->quantity * $item->unit_price);
        $vatTotal = $invoice->items->sum(fn (InvoiceItem $item) => $item->quantity * $item->unit_price * (($item->vat_rate ?? 0) / 100));
        $grandTotal = $subtotal + $vatTotal;

        $invoice->forceFill([
            'subtotal' => $subtotal,
            'vat_total' => $vatTotal,
            'grand_total' => $grandTotal,
        ])->save();
    }

    /**
     * Generate an installment payment plan for the given invoice.
     *
     * Creates equal monthly payments starting from the specified date.
     * The last installment includes any rounding differences.
     *
     * @param \App\Models\Invoice $invoice
     * @param int $installmentCount
     * @param string $firstDueDate
     * @return array
     * @throws \App\Exceptions\InstallmentPlanException
     */
    protected function generateInstallmentPlan(Invoice $invoice, int $installmentCount, string $firstDueDate): array
    {
        if ($installmentCount <= 0) {
            throw new InstallmentPlanException('Geçerli bir taksit sayısı giriniz.');
        }

        $startDate = Carbon::parse($firstDueDate);
        $total = (float) $invoice->patient_payable_amount;

        if ($total <= 0) {
            throw new InstallmentPlanException('Geçerli bir tutar bulunamadı.');
        }

        $baseAmount = round($total / $installmentCount, 2);

        $plan = [];
        $accumulated = 0.0;

        for ($index = 0; $index < $installmentCount; $index++) {
            $dueDate = $startDate->copy()->addMonths($index);
            $amount = ($index === $installmentCount - 1)
                ? round($total - $accumulated, 2)
                : $baseAmount;

            $plan[] = [
                'sequence' => $index + 1,
                'due_date' => $dueDate->format('Y-m-d'),
                'amount' => $amount,
                'status' => 'pending',
            ];

            $accumulated += $amount;
        }

        return $plan;
    }

    public function addPayment(Request $request, Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|in:cash,bank_transfer,credit_card,check',
            'paid_at' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $totalPaid = $invoice->payments()->sum('amount');
        $remainingBalance = max($invoice->grand_total - $totalPaid, 0);

        if ($validated['amount'] > $remainingBalance) {
            return back()->withErrors(['amount' => 'Ödeme tutarı kalan bakiyeden fazla olamaz.']);
        }

        $invoice->payments()->create([
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'paid_at' => Carbon::parse($validated['paid_at']),
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update invoice status based on payment
        $newTotalPaid = $totalPaid + $validated['amount'];

        if ($newTotalPaid >= $invoice->grand_total) {
            $invoice->update(['status' => InvoiceStatus::PAID]);
        } elseif ($newTotalPaid > 0) {
            $invoice->update(['status' => InvoiceStatus::PARTIAL]);
        }

        return redirect()->route('accounting.invoices.show', $invoice)->with('success', 'Ödeme başarıyla kaydedildi.');
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');

        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        // Validate that the status is a valid enum value
        try {
            $statusEnum = InvoiceStatus::from($validated['status']);
            $invoice->update(['status' => $statusEnum]);
        } catch (\ValueError $e) {
            return back()->withErrors(['status' => 'Geçersiz durum değeri.']);
        }

        return redirect()->route('accounting.invoices.show', $invoice)->with('success', 'Fatura durumu başarıyla güncellendi.');
    }

    /**
     * Fatura için ödeme yönetimi sayfasını gösterir.
     */
    public function payment(Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');

        $invoice->load([
            'patient',
            'payments' => fn ($query) => $query->orderBy('paid_at', 'desc'),
        ]);

        return view('accounting.invoices.payment', compact('invoice'));
    }

    /**
     * Faturaya yeni ödeme ekler.
     */
    public function storePayment(Request $request, Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,bank_transfer,credit_card,check',
            'paid_at' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $remainingBalance = $invoice->grand_total - $invoice->payments->sum('amount');

        if ($validated['amount'] > $remainingBalance) {
            return back()->withErrors(['amount' => 'Ödeme tutarı kalan bakiyeden fazla olamaz.']);
        }

        DB::transaction(function () use ($invoice, $validated) {
            $invoice->payments()->create([
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'paid_at' => Carbon::parse($validated['paid_at']),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Kalan bakiye kontrolü
            $totalPaid = $invoice->payments()->sum('amount');
            if ($totalPaid >= $invoice->grand_total) {
                $invoice->update(['status' => InvoiceStatus::PAID]);
            }
        });

        return redirect()->route('accounting.invoices.payment', $invoice)->with('success', 'Ödeme başarıyla eklendi.');
    }

    /**
     * Ödemeyi iptal eder.
     */
    public function removePayment(Invoice $invoice, Payment $payment)
    {
        $this->authorize('accessAccountingFeatures');

        if ($payment->invoice_id !== $invoice->id) {
            abort(404);
        }

        DB::transaction(function () use ($invoice, $payment) {
            $payment->delete();

            // Durum güncelleme
            $totalPaid = $invoice->payments()->sum('amount');
            if ($totalPaid == 0) {
                $invoice->update(['status' => InvoiceStatus::UNPAID]);
            } elseif ($totalPaid < $invoice->grand_total) {
                $invoice->update(['status' => InvoiceStatus::PARTIAL]);
            }
        });

        return redirect()->route('accounting.invoices.payment', $invoice)->with('success', 'Ödeme başarıyla iptal edildi.');
    }

    /**
     * Sigorta karşılama tutarını günceller.
     */
    public function updateInsuranceCoverage(Request $request, Invoice $invoice)
    {
        $this->authorize('accessAccountingFeatures');

        $validated = $request->validate([
            'insurance_coverage_amount' => 'required|numeric|min:0|max:' . $invoice->grand_total,
        ]);

        $totalPaid = $invoice->payments->sum('amount');

        if ($validated['insurance_coverage_amount'] > $totalPaid) {
            return back()->withErrors([
                'insurance_coverage_amount' => 'Sigorta karşılama tutarı yapılan ödemelerden fazla olamaz. Fazla ödemeleri iptal edin.'
            ]);
        }

        $invoice->update($validated);

        return redirect()->route('accounting.invoices.payment', $invoice)->with('success', 'Sigorta karşılama tutarı güncellendi.');
    }

}
