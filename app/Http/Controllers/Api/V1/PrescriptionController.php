<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;
use App\Http\Resources\Api\V1\PrescriptionResource;
use App\Models\Prescription;
use App\Services\PrescriptionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PrescriptionController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly PrescriptionService $prescriptionService)
    {
    }

    public function store(StorePrescriptionRequest $request): PrescriptionResource
    {
        $prescription = $this->prescriptionService->create($request->validated(), $request->user());

        return new PrescriptionResource($prescription);
    }

    public function show(Prescription $prescription): PrescriptionResource
    {
        $this->authorize('view', $prescription);

        return new PrescriptionResource($prescription->loadMissing('dentist:id,name'));
    }

    public function update(UpdatePrescriptionRequest $request, Prescription $prescription): PrescriptionResource
    {
        $prescription = $this->prescriptionService->update($prescription, $request->validated());

        return new PrescriptionResource($prescription);
    }

    public function destroy(Prescription $prescription)
    {
        $this->authorize('delete', $prescription);

        $this->prescriptionService->delete($prescription);

        return response()->noContent();
    }
}