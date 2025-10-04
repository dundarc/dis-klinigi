<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Models\Appointment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AppointmentCheckinController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('accessReceptionistFeatures', Appointment::class);

        $query = Appointment::with(['patient', 'dentist', 'encounter'])
            ->whereDate('start_at', today());

        $todaysAppointments = $query->orderBy('start_at')->get();

        return view('appointments.today', compact('todaysAppointments'));
    }

    public function checkIn(Request $request, Appointment $appointment)
    {
        $this->authorize('accessReceptionistFeatures', $appointment);

        if ($appointment->status === AppointmentStatus::CHECKED_IN || $appointment->status === AppointmentStatus::COMPLETED) {
            return back()->with('error', 'Bu hasta için zaten check-in yapılmış.');
        }

        $appointment->update([
            'status'        => AppointmentStatus::CHECKED_IN,
            'checked_in_at' => now(),
        ]);

        $appointment->encounter()->create([
            'patient_id' => $appointment->patient_id,
            'dentist_id' => $appointment->dentist_id,
            'type'       => EncounterType::SCHEDULED,
            'status'     => EncounterStatus::WAITING,
            'arrived_at' => now(),
        ]);

        return back()->with(
            'success',
            "{$appointment->patient->first_name} için check-in yapıldı ve bekleme odasına eklendi."
        );
    }

    public function markNoShow(Request $request, Appointment $appointment)
    {
        $this->authorize('accessReceptionistFeatures', $appointment);

        if (
            $appointment->status === AppointmentStatus::CHECKED_IN ||
            $appointment->status === AppointmentStatus::COMPLETED ||
            $appointment->status === AppointmentStatus::NO_SHOW
        ) {
            return back()->with('error', 'Bu randevu için zaten işlem yapılmış.');
        }

        $appointment->update(['status' => AppointmentStatus::NO_SHOW]);

        return back()->with(
            'success',
            "{$appointment->patient->first_name} için 'Gelmedi' olarak işaretlendi."
        );
    }
}
