<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Http\Requests\Api\V1\ReferAppointmentRequest;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\AppointmentResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\NotificationService;
use App\Models\User;

class AppointmentReferralController extends Controller
{
    use AuthorizesRequests;

    /**
     * Servis sınıfını constructor (yapıcı metod) aracılığıyla enjekte ediyoruz.
     * Bu sayede controller içinde $this->notificationService olarak kullanabiliriz.
     */
    public function __construct(protected NotificationService $notificationService)
    {
    }

    /**
     * Mevcut bir randevuyu başka bir hekime sevk eder.
     *
     * @param  \App\Http\Requests\Api\V1\ReferAppointmentRequest  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \App\Http\Resources\Api\V1\AppointmentResource
     */
    public function store(ReferAppointmentRequest $request, Appointment $appointment)
    {
        $validated = $request->validated();

        $originalDentist = $appointment->dentist; // Sevk eden (eski) hekimi sakla
        $newDentistId = $validated['referred_to_user_id'];

        $appointment->update([
            'referred_from_user_id' => $request->user()->id,
            'dentist_id'              => $newDentistId, // Randevuyu yeni hekime ata
            'referral_status'         => 'pending',
            'referral_notes'          => $validated['referral_notes'] ?? null,
        ]);

        // Sevkin gönderildiği yeni hekime bildirim gönder
        $newDentist = User::find($newDentistId);
        $patientName = $appointment->patient->first_name . ' ' . $appointment->patient->last_name;
        
        $this->notificationService->createNotification(
            $newDentist,
            'Yeni Randevu Sevki',
            "Dr. {$originalDentist->name}, {$patientName} isimli hastanın randevusunu size sevk etti.",
            // Gelecekte arayüzde ilgili randevuya gitmesi için bir link de ekleyebiliriz:
            // url('/appointments/' . $appointment->id) 
        );

        return new AppointmentResource($appointment->load(['patient','dentist']));
    }

    /**
     * Bir hekimin, kendisine sevk edilmiş bir randevuyu kabul etmesini sağlar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \App\Http\Resources\Api\V1\AppointmentResource
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Yetki kontrolü: Kullanıcı bu sevki kabul edebilir mi?
        $this->authorize('acceptReferral', $appointment);

        $appointment->update(['referral_status' => 'accepted']);

        // Opsiyonel: Sevki gönderen hekime kabul edildiğine dair bildirim gönderilebilir.
        if ($appointment->referred_from_user_id) {
            $originalDentist = User::find($appointment->referred_from_user_id);
            if ($originalDentist) {
                $patientName = $appointment->patient->first_name . ' ' . $appointment->patient->last_name;
                $this->notificationService->createNotification(
                    $originalDentist,
                    'Sevk Kabul Edildi',
                    "{$patientName} için yaptığınız sevk, Dr. {$appointment->dentist->name} tarafından kabul edildi."
                );
            }
        }

        return new AppointmentResource($appointment->load(['patient','dentist']));
    }
}