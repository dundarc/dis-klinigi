<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTreatmentRequest;
use App\Http\Requests\UpdateTreatmentRequest;
use App\Models\Treatment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SystemTreatmentController extends Controller
{
    public function index(): View
    {
        $treatments = Treatment::withTrashed()
            ->orderByRaw('deleted_at IS NULL DESC')
            ->orderBy('name')
            ->paginate(20);

        return view('system.treatments.index', compact('treatments'));
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
}
