<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\AppointmentService;
use App\Services\TreatmentPlanDateService;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\Api\V1\AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppointmentController extends Controller
{
    use AuthorizesRequests;

    protected $appointmentService;
    protected $dateService;

    public function __construct(
        AppointmentService $appointmentService,
        TreatmentPlanDateService $dateService
    ) {
        $this->appointmentService = $appointmentService;
        $this->dateService = $dateService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);

        // Boş filtre parametrelerini diziye çevir veya null yap
        $request->merge([
            'dentist_id' => $request->input('dentist_id') ? (array) $request->input('dentist_id') : null,
            'status' => $request->input('status') ? (array) $request->input('status') : null,
        ]);

        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'dentist_id' => 'nullable|array',
            'dentist_id.*' => 'integer|exists:users,id',
            'status' => 'nullable|array',
            'status.*' => 'string',
        ]);

        $appointments = $this->appointmentService->getAppointments(
            $validated['start'],
            $validated['end'],
            $validated['dentist_id'] ?? null,
            $validated['status'] ?? null
        );
        
        // Resource collection'ı doğrudan döndürerek düz bir JSON dizisi elde ediyoruz.
        return AppointmentResource::collection($appointments);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->appointmentService->createAppointment($request->validated());
        return new AppointmentResource($appointment->load(['patient', 'dentist']));
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return new AppointmentResource($appointment->load(['patient', 'dentist']));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $this->appointmentService->updateAppointment($appointment, $request->validated());
        return new AppointmentResource($appointment->fresh()->load(['patient', 'dentist']));
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $this->appointmentService->deleteAppointment($appointment);
        return response()->noContent();
    }
}

