<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Http\Requests\Api\V1\StoreAppointmentRequest;
use App\Http\Requests\Api\V1\UpdateAppointmentRequest;
use App\Http\Resources\Api\V1\AppointmentResource;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use App\Enums\AppointmentStatus;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Carbon;

class AppointmentController extends Controller
{
    use AuthorizesRequests; // Yetkilendirme için GEREKLİ

     public function call(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $appointment->update([
            'status' => AppointmentStatus::IN_SERVICE, // Durumu "İşlemde" yap
            'called_at' => now(), // Çağırılma zamanını kaydet
        ]);

        return new AppointmentResource($appointment->load(['patient', 'dentist']));
    }
    
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Appointment::with(['patient', 'dentist']);

        if ($user->role === UserRole::DENTIST) {
            $query->where('dentist_id', $user->id);
        } else {
            $this->authorize('viewAny', Appointment::class);
        }
        
        if ($request->has('dentist_id')) {
            $query->where('dentist_id', $request->dentist_id);
        }
        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('start_at', [$request->from, $request->to]);
        }

        return AppointmentResource::collection($query->paginate(25));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = Appointment::create($request->validated());
        return new AppointmentResource($appointment->load(['patient', 'dentist']));
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return new AppointmentResource($appointment->load(['patient', 'dentist']));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());
        return new AppointmentResource($appointment->load(['patient', 'dentist']));
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();
        return response()->noContent();
    }
    
    public function checkIn(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $appointment->update([
            'status' => AppointmentStatus::CHECKED_IN,
            'checked_in_at' => now(),
        ]);
        return new AppointmentResource($appointment->load(['patient', 'dentist']));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $validated = $request->validate([
            'status' => ['required', new Enum(AppointmentStatus::class)],
        ]);

        $appointment->update($validated);

        return new AppointmentResource($appointment->load(['patient', 'dentist']));
    }

    public function dentistSchedule(Request $request, User $dentist)
    {
        if ($dentist->role !== UserRole::DENTIST) {
            abort(404);
        }

        $user = $request->user();

        if ($user->role === UserRole::DENTIST && $user->id !== $dentist->id) {
            abort(403);
        }

        if (! in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST], true)) {
            abort(403);
        }

        $date = $request->input('date');
        $day = $date ? Carbon::parse($date) : Carbon::today();

        $appointments = Appointment::with('patient')
            ->where('dentist_id', $dentist->id)
            ->whereBetween('start_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
            ->orderBy('start_at')
            ->get();

        $data = $appointments->map(function (Appointment $appointment) {
            return [
                'id' => $appointment->id,
                'start' => $appointment->start_at->toIso8601String(),
                'end' => $appointment->end_at->toIso8601String(),
                'patient' => $appointment->patient?->full_name,
                'status' => $appointment->status->value,
            ];
        });

        return response()->json(['data' => $data]);
    }
}