<?php

namespace App\Http\Controllers;

use App\Models\TreatmentPlan;
use App\Http\Requests\StoreTreatmentPlanRequest;
use App\Http\Requests\UpdateTreatmentPlanRequest;
use App\Models\Patient;
use App\Services\PdfExportService;
use App\Services\TreatmentPlanService;
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
            'treatmentPlan' => $treatmentPlan,
            'patient' => $patient
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TreatmentPlan $treatmentPlan)
    {
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

        // Load basic treatment plan info without items (will be loaded via AJAX)
        $treatmentPlan->load('patient:id,first_name,last_name');

        return view('treatment_plans.edit', [
            'treatmentPlan' => $treatmentPlan,
            'dentists' => $dentists,
            'treatments' => $treatments,
            'fileTypes' => $fileTypes,
            'planStatuses' => $planStatuses
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTreatmentPlanRequest $request, TreatmentPlan $treatmentPlan)
    {
        $validated = $request->validated();

        // Handle new items if any
        if ($request->has('new_items')) {
            $validated['new_items'] = $request->input('new_items', []);
        }

        $updatedPlan = $this->treatmentPlanService->updatePlan($treatmentPlan, $validated);

        // Check if it's an AJAX request (autosave)
        if ($request->expectsJson()) {
            // Reload the plan with updated items
            $updatedPlan->load(['items.treatment', 'items.appointment']);
            
            return response()->json([
                'success' => true,
                'message' => 'Treatment plan updated successfully.',
                'updated_items' => $updatedPlan->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'treatment_id' => $item->treatment_id,
                        'tooth_number' => $item->tooth_number,
                        'estimated_price' => $item->estimated_price,
                        'status' => $item->status->value,
                        'treatment_plan_id' => $item->treatment_plan_id,
                    ];
                }),
                'total_cost' => $updatedPlan->total_estimated_cost
            ]);
        }

        return redirect()->route('treatment-plans.show', $updatedPlan)->with('success', 'Treatment plan updated successfully.');
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
        $plan = TreatmentPlan::with([
            'patient',
            'dentist',
            'items.treatment',
            'items.appointment.dentist',
            'items.histories.user',
            'items.appointmentHistory.user',
        ])->findOrFail($treatmentPlan->id);

        return view('treatment-plans.pdf', compact('plan'));
    }

    public function generateInvoice(Request $request, TreatmentPlan $treatmentPlan)
    {
        $request->validate([
            'encounter_id' => 'required|exists:encounters,id',
            'item_ids' => 'nullable|array',
            'item_ids.*' => 'exists:treatment_plan_items,id'
        ]);

        $encounter = \App\Models\Encounter::find($request->encounter_id);
        $invoice = $this->treatmentPlanService->generateInvoiceFromEncounterItems($encounter);

        if ($invoice) {
            return redirect()->route('invoices.show', $invoice)->with('success', 'Fatura başarıyla oluşturuldu.');
        }

        return back()->with('error', 'Faturalanacak tamamlanmış tedavi bulunamadı.');
    }

    /**
     * Autosave the specified resource in storage.
     */
    public function autosave(UpdateTreatmentPlanRequest $request, TreatmentPlan $treatmentPlan)
    {
        try {
            $validated = $request->validated();

            $updatedPlan = $this->treatmentPlanService->updatePlan($treatmentPlan, $validated);
            
            // Reload the plan with updated items
            $updatedPlan->load(['items.treatment', 'items.appointment']);

            return response()->json([
                'success' => true,
                'message' => 'Treatment plan autosaved successfully.',
                'updated_items' => $updatedPlan->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'treatment_id' => $item->treatment_id,
                        'tooth_number' => $item->tooth_number,
                        'estimated_price' => $item->estimated_price,
                        'status' => $item->status->value,
                        'treatment_plan_id' => $item->treatment_plan_id,
                    ];
                }),
                'total_cost' => $updatedPlan->total_estimated_cost
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to autosave: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show cost comparison report for a treatment plan
     */
    public function costReport(TreatmentPlan $treatmentPlan)
    {
        $treatmentPlan->load(['patient', 'dentist', 'items.treatment', 'items.histories.user']);

        $costSummary = $this->treatmentPlanService->getCostSummary($treatmentPlan);

        // Get detailed breakdown
        $itemsBreakdown = $treatmentPlan->items->map(function ($item) {
            $invoicedAmount = 0;

            // Find invoice items for this treatment plan item
            $invoiceItems = \App\Models\InvoiceItem::where('description', 'like', '%' . $item->treatment->name . '%')
                ->whereHas('invoice', function ($query) use ($item) {
                    $query->where('patient_id', $item->treatmentPlan->patient_id);
                })
                ->get();

            foreach ($invoiceItems as $invoiceItem) {
                if (str_contains($invoiceItem->description, $item->treatment->name)) {
                    $invoicedAmount += $invoiceItem->line_total;
                }
            }

            return [
                'item' => $item,
                'estimated' => $item->estimated_price,
                'invoiced' => $invoicedAmount,
                'variance' => $invoicedAmount - $item->estimated_price,
                'variance_percent' => $item->estimated_price > 0 ? round((($invoicedAmount - $item->estimated_price) / $item->estimated_price) * 100, 2) : 0,
            ];
        });

        return view('treatment_plans.cost-report', compact('treatmentPlan', 'costSummary', 'itemsBreakdown'));
    }
}