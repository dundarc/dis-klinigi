<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use App\Enums\EncounterType;
use App\Enums\EncounterStatus;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppointmentCheckinController extends Controller
{
    use AuthorizesRequests;

    /**
     * O güne ait tüm randevuları listeler.
     */
    public function index(Request $request)
    {
        // Yetki kontrolü: Bu sayfaya sadece Admin ve Resepsiyonist erişebilir.
        $this->authorize('accessReceptionistFeatures', Appointment::class);

        $query = Appointment::with(['patient', 'dentist', 'encounter'])
            ->whereDate('start_at', today());

        $todaysAppointments = $query->orderBy('start_at')->get();

        return view('appointments.today', compact('todaysAppointments'));
    }

    /**
     * Bir randevu için check-in işlemi yapar ve bir ziyaret kaydı (encounter) oluşturur.
     */
    public function checkIn(Request $request, Appointment $appointment)
    {
        $this->authorize('accessReceptionistFeatures', $appointment);

        // Eğer hasta zaten giriş yapmışsa veya işlem bittiyse tekrar check-in yapmayı engelle.
        if ($appointment->status === AppointmentStatus::CHECKED_IN || $appointment->status === AppointmentStatus::COMPLETED) {
            return back()->with('error', 'Bu hasta için zaten giriş yapılmış.');
        }

        // 1. Randevu durumunu 'checked_in' olarak güncelle.
        $appointment->update(['status' => AppointmentStatus::CHECKED_IN]);

        // 2. Yeni bir Ziyaret Kaydı (Encounter) oluştur.
        $appointment->encounter()->create([
            'patient_id' => $appointment->patient_id,
            'dentist_id' => $appointment->dentist_id,
            'type' => EncounterType::SCHEDULED,
            'status' => EncounterStatus::WAITING,
            'arrived_at' => now(),
        ]);

        return back()->with('success', "{$appointment->patient->first_name} için check-in yapıldı ve bekleme odasına eklendi.");
    }
}

