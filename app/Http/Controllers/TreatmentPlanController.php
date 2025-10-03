<?php

namespace App\Http\Controllers;

use App\Models\TreatmentPlan;
use App\Http\Requests\StoreTreatmentPlanRequest;
use App\Http\Requests\UpdateTreatmentPlanRequest;
use App\Models\Patient;
use App\Services\PdfExportService;
use App\Services\TreatmentPlanService;
use App\Enums\TreatmentPlanItemStatus;
use Illuminate\Http\Request;

class TreatmentPlanController extends Controller
{
    protected TreatmentPlanService $treatmentPlanService;
    protected PdfExportService $pdfExportService;

    public function __construct(
        TreatmentPlanService $treatmentPlanService,
        PdfExportService $pdfExportService
    ) {
        $this->treatmentPlanService = $treatmentPlanService;
        $this->pdfExportService = $pdfExportService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Patient $patient)
    {
        // This will likely be displayed on the patient's profile page
        $plans = $patient->treatmentPlans()->with('dentist')->latest()->get();
        return view('patients.show', compact('patient', 'plans')); // Assuming a tab on patient show view
    }

    /**
     * Display all treatment plans with pagination and search.
     */
    public function all(Request $request)
    {
        $query = TreatmentPlan::with(['patient:id,first_name,last_name,national_id,phone_primary', 'dentist:id,name']);

        // Apply search if provided
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('national_id', 'like', "%{$search}%")
                                ->orWhere('phone_primary', 'like', "%{$search}%")
                                ->orWhere('phone_secondary', 'like', "%{$search}%");
                });
            });
        }

        $plans = $query->latest()->paginate(20);

        // Transform data for consistent format
        $transformedPlans = $plans->map(function ($plan) {
            return [
                'id' => $plan->id,
                'patient_name' => $plan->patient->first_name . ' ' . $plan->patient->last_name,
                'patient_tc' => $plan->patient->national_id,
                'patient_phone' => $plan->patient->phone_primary ?: $plan->patient->phone_secondary,
                'dentist_name' => $plan->dentist->name,
                'status' => $plan->status->value,
                'total_estimated_cost' => $plan->total_estimated_cost,
                'created_at' => $plan->created_at->toISOString(),
            ];
        });

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $transformedPlans,
                'current_page' => $plans->currentPage(),
                'last_page' => $plans->lastPage(),
                'from' => $plans->firstItem() ?: 0,
                'to' => $plans->lastItem() ?: 0,
                'total' => $plans->total(),
                'per_page' => $plans->perPage(),
            ]);
        }

        return view('treatment-plans.all', [
            'title' => 'Tedavi Planları',
            'plans' => $plans,
            'transformedPlans' => $transformedPlans
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Patient $patient)
    {
        // dentists, treatments list for the form
        $dentists = \App\Models\User::where('role', \App\Enums\UserRole::DENTIST)->get();
        $treatments = \App\Models\Treatment::all();
        return view('treatment_plans.create', compact('patient', 'dentists', 'treatments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTreatmentPlanRequest $request)
    {
        $validated = $request->validated();
        $plan = $this->treatmentPlanService->createPlan(
            Patient::find($validated['patient_id']),
            \App\Models\User::find($validated['dentist_id']),
            $validated['items'],
            $validated['notes'] ?? null
        );

        return redirect()->route('treatment-plans.show', $plan)->with('success', 'Treatment plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TreatmentPlan $treatmentPlan)
    {
        $treatmentPlan->load([
            'dentist',
            'items.treatment',
            'items.appointment.dentist',
            'items.appointmentHistory.appointment',
            'items.appointmentHistory.user',
            'items.histories.user'
        ]);
        $patient = $treatmentPlan->patient()->first();
        return view('treatment_plans.show', [
            'title' => 'Tedavi Planı',
            'treatmentPlan' => $treatmentPlan,
            'patient' => $patient
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TreatmentPlan $treatmentPlan)
    {
        // Null relation safety check - tedavi planının geçerli bir hastası olduğundan emin ol
        if (!$treatmentPlan->patient) {
            abort(404, 'Treatment plan patient not found');
        }

        $dentists = \App\Models\User::where('role', \App\Enums\UserRole::DENTIST)->select('id', 'name')->get()->map(function($d) {
            return ['id' => $d->id, 'name' => $d->name];
        })->toArray();
        $treatments = \App\Models\Treatment::select('id', 'name', 'default_price')->get()->map(function($t) {
            return ['id' => $t->id, 'name' => $t->name, 'default_price' => $t->default_price];
        })->toArray();
        $fileTypes = collect(\App\Enums\FileType::cases())->map(function($case) {
            return ['value' => $case->value, 'label' => ucfirst($case->value)];
        })->toArray();
        $planStatuses = collect(\App\Enums\TreatmentPlanStatus::cases())->map(function($case) {
            return ['value' => $case->value, 'label' => $case->label()];
        })->toArray();

        // Load treatment plan with all necessary relations
        $treatmentPlan->load([
            'patient:id,first_name,last_name',
            'items.treatment:id,name,default_price',
            'items.appointment:id,start_at'
        ]);

        // Transform items for frontend
        $items = $treatmentPlan->items->map(function ($item) {
            return [
                'id' => $item->id,
                'treatment_id' => $item->treatment_id ? $item->treatment_id : '',
                'treatment_name' => $item->treatment ? $item->treatment->name : '',
                'tooth_number' => $item->tooth_number ?? '',
                'appointment_date' => $item->appointment ? $item->appointment->start_at->format('Y-m-d\TH:i') : '',
                'estimated_price' => max($item->estimated_price ?? 0.00, 0.01), // Minimum 0.01
                'status' => $item->status->value,
                'treatment_plan_id' => $item->treatment_plan_id
            ];
        })->toArray();

        return view('treatment_plans.edit', [
            'treatmentPlan' => $treatmentPlan,
            'dentists' => $dentists,
            'treatments' => $treatments,
            'fileTypes' => $fileTypes,
            'planStatuses' => $planStatuses,
            'items' => $items
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTreatmentPlanRequest $request, TreatmentPlan $treatmentPlan)
    {
        try {
            $validated = $request->validated();

            // Handle new items if any (for auto-save compatibility)
            if ($request->has('new_items')) {
                $validated['new_items'] = $request->input('new_items', []);
            }

            // Handle deleted items if any
            if ($request->has('deleted_items')) {
                $validated['deleted_items'] = $request->input('deleted_items', []);
            }

            $updatedPlan = $this->treatmentPlanService->updatePlan($treatmentPlan, $validated);

            // Check if it's an AJAX request (autosave)
            if ($request->expectsJson()) {
                // Reload the plan with updated items and relations
                $updatedPlan->load(['items.treatment', 'items.appointment']);

                return response()->json([
                    'success' => true,
                    'message' => 'Tedavi planı başarıyla güncellendi.',
                    'updated_items' => $updatedPlan->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'treatment_id' => $item->treatment_id,
                            'treatment_name' => $item->treatment->name ?? '',
                            'tooth_number' => $item->tooth_number,
                            'appointment_date' => $item->appointment?->start_at?->format('Y-m-d\TH:i'),
                            'estimated_price' => $item->estimated_price,
                            'status' => $item->status->value,
                            'treatment_plan_id' => $item->treatment_plan_id,
                        ];
                    }),
                    'total_cost' => $updatedPlan->total_estimated_cost,
                    'plan_status' => $updatedPlan->status->value,
                    'updated_at' => $updatedPlan->updated_at->toISOString()
                ]);
            }

            return redirect()->route('treatment-plans.show', $updatedPlan)->with('success', 'Tedavi planı başarıyla güncellendi.');
        } catch (\InvalidArgumentException $e) {
            // Validation/data related errors
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            return back()->withErrors(['general' => $e->getMessage()]);
        } catch (\Exception $e) {
            // General errors
            \Illuminate\Support\Facades\Log::error('Treatment plan update failed', [
                'plan_id' => $treatmentPlan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tedavi planı güncellenirken bir hata oluştu.'
                ], 500);
            }
            return back()->withErrors(['general' => 'Tedavi planı güncellenirken bir hata oluştu.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TreatmentPlan $treatmentPlan)
    {
        $patient = $treatmentPlan->patient;
        $treatmentPlan->delete();
        return redirect()->route('patients.show', $patient)->with('success', 'Treatment plan deleted successfully.');
    }

    /**
     * Generate a PDF for the specified treatment plan.
     */
    public function downloadPdf(TreatmentPlan $treatmentPlan)
    {
        return $this->pdfExportService->generateTreatmentPlanPdf($treatmentPlan);
    }

    /**
     * Show PDF view for the specified treatment plan.
     */
    public function pdf(TreatmentPlan $treatmentPlan)
    {
        // Debug için basit eager loading - adım adım yükle
        $plan = TreatmentPlan::with([
            'patient:id,first_name,last_name,national_id,phone_primary',
            'dentist:id,name',
            'items.treatment:id,name',
            'items.appointment.dentist:id,name'
        ])->findOrFail($treatmentPlan->id);

        // Ek ilişkileri ayrı yükle
        if ($plan->items->count() > 0) {
            $plan->load([
                'items.histories.user:id,name',
                'items.appointmentHistory.user:id,name'
            ]);
        }

        return view('treatment-plans.pdf', compact('plan'));
    }


    /**
     * Autosave the specified resource in storage.
     */
    public function autosave(UpdateTreatmentPlanRequest $request, TreatmentPlan $treatmentPlan)
    {
        try {
            $validated = $request->validated();

            // Handle new items if any (for auto-save compatibility)
            if ($request->has('new_items')) {
                $validated['new_items'] = $request->input('new_items', []);
            }

            // Handle deleted items if any
            if ($request->has('deleted_items')) {
                $validated['deleted_items'] = $request->input('deleted_items', []);
            }

            $updatedPlan = $this->treatmentPlanService->updatePlan($treatmentPlan, $validated);

            // Reload the plan with updated items and relations
            $updatedPlan->load(['items.treatment', 'items.appointment']);

            return response()->json([
                'success' => true,
                'message' => 'Tedavi planı otomatik kaydedildi.',
                'updated_items' => $updatedPlan->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'treatment_id' => $item->treatment_id,
                        'treatment_name' => $item->treatment->name ?? '',
                        'tooth_number' => $item->tooth_number,
                        'appointment_date' => $item->appointment?->start_at?->format('Y-m-d\TH:i'),
                        'estimated_price' => $item->estimated_price,
                        'status' => $item->status->value,
                        'treatment_plan_id' => $item->treatment_plan_id,
                    ];
                }),
                'total_cost' => $updatedPlan->total_estimated_cost,
                'plan_status' => $updatedPlan->status->value,
                'autosaved_at' => now()->toISOString()
            ]);
        } catch (\InvalidArgumentException $e) {
            // Validation/data related errors
            return response()->json([
                'success' => false,
                'message' => 'Otomatik kaydetme başarısız: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            // General errors - log for debugging
            \Illuminate\Support\Facades\Log::error('Treatment plan autosave failed', [
                'plan_id' => $treatmentPlan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Otomatik kaydetme sırasında bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * Show cost comparison report for a treatment plan
     */
    public function costReport(TreatmentPlan $treatmentPlan)
    {
        // Update invoice_status for items that have invoices but status not set
        foreach ($treatmentPlan->items as $item) {
            if ($item->invoice_status !== 'invoiced') {
                $patientTreatments = \App\Models\PatientTreatment::where('treatment_plan_item_id', $item->id)->get();
                $hasInvoice = \App\Models\InvoiceItem::whereIn('patient_treatment_id', $patientTreatments->pluck('id'))
                    ->whereHas('invoice', function ($query) {
                        $query->whereNull('deleted_at');
                    })->exists();
                if ($hasInvoice) {
                    $item->update(['invoice_status' => 'invoiced']);
                }
            }

            // Assign appointments to completed items that don't have one
            if ($item->status === \App\Enums\TreatmentPlanItemStatus::DONE && !$item->appointment_id) {
                $encounter = $item->encounters()->first();
                if ($encounter) {
                    if ($encounter->appointment_id) {
                        $item->update(['appointment_id' => $encounter->appointment_id]);
                    } else {
                        // Create appointment for walk-in encounters
                        $appointment = \App\Models\Appointment::create([
                            'patient_id' => $item->treatmentPlan->patient_id,
                            'dentist_id' => $encounter->dentist_id,
                            'start_at' => $encounter->started_at ?? $encounter->arrived_at ?? now(),
                            'end_at' => ($encounter->started_at ?? $encounter->arrived_at ?? now())->addMinutes(30),
                            'status' => \App\Enums\AppointmentStatus::COMPLETED,
                            'notes' => 'Otomatik olarak tedavi tamamlanmasından oluşturuldu.',
                        ]);
                        $item->update(['appointment_id' => $appointment->id]);
                    }
                }
            }
        }

        $treatmentPlan->load(['patient', 'dentist', 'items.treatment', 'items.histories.user']);

        $costSummary = $this->treatmentPlanService->getCostSummary($treatmentPlan);

        // Get detailed breakdown
        $itemsBreakdown = $treatmentPlan->items->map(function ($item) use ($treatmentPlan) {
            $invoicedAmount = 0;
            $paidAmount = 0;

            // Method 1: Find invoices through PatientTreatment records (existing method)
            $patientTreatments = \App\Models\PatientTreatment::where('treatment_plan_item_id', $item->id)->get();
            $invoiceItemsFromTreatments = \App\Models\InvoiceItem::whereIn('patient_treatment_id', $patientTreatments->pluck('id'))
                ->whereHas('invoice', function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->with('invoice.payments')
                ->get();

            foreach ($invoiceItemsFromTreatments as $invoiceItem) {
                $invoicedAmount += $invoiceItem->line_total;

                // Calculate paid amount for this invoice item
                $invoice = $invoiceItem->invoice;
                if ($invoice) {
                    $totalPaidForInvoice = $invoice->payments->sum('amount');
                    $itemRatio = $invoiceItem->line_total / $invoice->grand_total;
                    $paidAmount += $totalPaidForInvoice * $itemRatio;
                }
            }

            return [
                'item' => $item,
                'estimated' => $item->estimated_price,
                'invoiced' => $invoicedAmount,
                'paid' => min($paidAmount, $invoicedAmount), // Don't exceed invoiced amount
                'variance' => $invoicedAmount - $item->estimated_price,
                'variance_percent' => $item->estimated_price > 0 ? round((($invoicedAmount - $item->estimated_price) / $item->estimated_price) * 100, 2) : 0,
            ];
        });


        return view('treatment_plans.cost-report', compact('treatmentPlan', 'costSummary', 'itemsBreakdown'));
    }
}