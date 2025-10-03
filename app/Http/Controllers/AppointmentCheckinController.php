<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\EmailAutomationSetting;
use App\Models\Setting;
use App\Services\EmailService;
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
        $encounter = $appointment->encounter()->create([
            'patient_id' => $appointment->patient_id,
            'dentist_id' => $appointment->dentist_id,
            'type' => EncounterType::SCHEDULED,
            'status' => EncounterStatus::WAITING,
            'arrived_at' => now(),
        ]);

        // 3. Otomatik e-posta bildirimi gönder (eğer aktifse)
        $automationSettings = EmailAutomationSetting::getSettings();
        if ($automationSettings && $automationSettings->patient_checkin_to_dentist) {
            try {
                EmailService::sendTemplate('patient_checkin_notification', [
                    'to' => $appointment->dentist->email,
                    'to_name' => $appointment->dentist->name,
                    'data' => [
                        'patient_name' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                        'dentist_name' => $appointment->dentist->name,
                        'appointment_time' => $appointment->start_at->format('d.m.Y H:i'),
                        'clinic_name' => Setting::where('key', 'clinic_name')->first()?->value ?? 'Klinik'
                    ]
                ]);
            } catch (\Exception $e) {
                // E-posta gönderimi başarısız olsa bile check-in işlemi devam eder
                \Log::error('Patient check-in email failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', "{$appointment->patient->first_name} için check-in yapıldı ve bekleme odasına eklendi.");
    }

    /**
     * Bir randevu için gelmedi işlemi yapar.
     */
    public function markNoShow(Request $request, Appointment $appointment)
    {
        $this->authorize('accessReceptionistFeatures', $appointment);

        // Eğer hasta zaten giriş yapmışsa veya işlem bittiyse tekrar işlem yapmayı engelle.
        if ($appointment->status === AppointmentStatus::CHECKED_IN ||
            $appointment->status === AppointmentStatus::COMPLETED ||
            $appointment->status === AppointmentStatus::NO_SHOW) {
            return back()->with('error', 'Bu randevu için zaten işlem yapılmış.');
        }

        // Randevu durumunu 'no_show' olarak güncelle.
        $appointment->update(['status' => AppointmentStatus::NO_SHOW]);

        return back()->with('success', "{$appointment->patient->first_name} için 'Gelmedi' olarak işaretlendi.");
    }
}

