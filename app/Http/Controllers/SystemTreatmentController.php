<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTreatmentRequest;
use App\Http\Requests\UpdateTreatmentRequest;
use App\Models\Treatment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class SystemTreatmentController extends Controller
{
    public function index(): View
    {
        $treatments = Treatment::withTrashed()
            ->orderByRaw('deleted_at IS NULL DESC')
            ->orderBy('name')
            ->paginate(20);

        // KDV statistics for the view
        $vatStats = [
            '0' => Treatment::where('default_vat', 0)->count(),
            '8' => Treatment::where('default_vat', 8)->count(),
            '10' => Treatment::where('default_vat', 10)->count(),
            '18' => Treatment::where('default_vat', 18)->count(),
            '20' => Treatment::where('default_vat', 20)->count(),
        ];
        $otherVat = Treatment::whereNotIn('default_vat', [0, 8, 10, 18, 20])->count();

        return view('system.treatments.index', compact('treatments', 'vatStats', 'otherVat'));
    }

    public function create(): View
    {
        return view('system.treatments.create');
    }

    public function store(StoreTreatmentRequest $request): RedirectResponse
    {
        Treatment::create($request->validated());

        return redirect()
            ->route('system.treatments.index')
            ->with('success', 'Tedavi başarıyla eklendi.');
    }

    public function edit(Treatment $treatment): View
    {
        return view('system.treatments.edit', compact('treatment'));
    }

    public function update(UpdateTreatmentRequest $request, Treatment $treatment): RedirectResponse
    {
        $treatment->update($request->validated());

        return redirect()
            ->route('system.treatments.index')
            ->with('success', 'Tedavi bilgileri güncellendi.');
    }

    public function destroy(Treatment $treatment): RedirectResponse
    {
        $treatment->delete();

        return redirect()
            ->route('system.treatments.index')
            ->with('success', 'Tedavi silindi.');
    }

    /**
     * Bulk update KDV rates for treatments
     */
    public function bulkUpdateVat(Request $request): RedirectResponse
    {
        $request->validate([
            'vat_rate' => 'required|numeric|min:0|max:100',
            'treatment_ids' => 'nullable|array',
            'treatment_ids.*' => 'exists:treatments,id',
        ]);

        $vatRate = $request->input('vat_rate');
        $treatmentIds = $request->input('treatment_ids');

        if ($treatmentIds) {
            // Update specific treatments
            Treatment::whereIn('id', $treatmentIds)->update(['default_vat' => $vatRate]);
            $count = count($treatmentIds);
            $message = "{$count} tedavi için KDV oranı %{$vatRate} olarak güncellendi.";
        } else {
            // Update all treatments
            $count = Treatment::count();
            Treatment::query()->update(['default_vat' => $vatRate]);
            $message = "Tüm tedaviler için KDV oranı %{$vatRate} olarak güncellendi ({$count} tedavi).";
        }

        return redirect()
            ->route('system.treatments.index')
            ->with('success', $message);
    }

    /**
     * Set all treatments to 10% KDV (current Turkish medical KDV rate)
     */
    public function setMedicalVatRate(): RedirectResponse
    {
        $medicalVatRate = 10; // Current Turkish medical services KDV rate
        $count = Treatment::count();

        Treatment::query()->update(['default_vat' => $medicalVatRate]);

        return redirect()
            ->route('system.treatments.index')
            ->with('success', "Tüm tedaviler için tıbbi hizmet KDV oranı %{$medicalVatRate} olarak ayarlandı ({$count} tedavi).");
    }
}
