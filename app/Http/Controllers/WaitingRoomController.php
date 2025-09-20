<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\User;
use App\Enums\AppointmentStatus;
use App\Enums\EncounterStatus;
use App\Enums\UserRole;

class WaitingRoomController extends Controller
{
    /**
     * Bekleme Odası sayfasını, check-in yapmış ve acil bekleyen
     * hastaların listesiyle birlikte görüntüler.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Durumu "Check-in" olan planlı randevular.
        $checkedInAppointments = Appointment::with(['patient', 'dentist'])
            ->where('status', AppointmentStatus::CHECKED_IN)
            ->orderBy('checked_in_at', 'asc')
            ->get();

        // 2. Durumu "Waiting" olan acil/walk-in vakaları.
        $waitingEncounters = Encounter::with(['patient', 'dentist'])
            ->where('status', EncounterStatus::WAITING)
            ->orderByRaw("
                CASE triage_level
                    WHEN 'red' THEN 1
                    WHEN 'yellow' THEN 2
                    WHEN 'green' THEN 3
                    ELSE 4
                END ASC
            ")
            ->orderBy('arrived_at', 'asc')
            ->get();
        
        // 3. Hekim atama modalı için tüm hekimlerin listesi.
        // --- EKSİK OLAN KISIM BUYDU ---
        $allDentists = User::where('role', UserRole::DENTIST)->orderBy('name')->get();

        // Tüm verileri view'e gönder.
        return view('waiting-room.index', compact(
            'checkedInAppointments', 
            'waitingEncounters',
            'allDentists' // Değişkeni buraya ekliyoruz.
        ));
    }
}