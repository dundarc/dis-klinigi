<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\Invoice;
use App\Enums\AppointmentStatus;
use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Enums\InvoiceStatus;

class DashboardController extends Controller
{
    /**
     * Dashboard için gerekli özet verileri toplar ve arayüzü gösterir.
     */
    public function index()
    {
        // 1. Check-in yapmamış hastalar listesi (bugünkü, ilk 5)
        $uncheckedAppointments = Appointment::with('patient', 'dentist')
            ->whereDate('start_at', today())
            ->whereIn('status', [AppointmentStatus::SCHEDULED, AppointmentStatus::CONFIRMED])
            ->orderBy('start_at')
            ->take(5) // Sadece ilk 5'i al
            ->get();

        // 2. Tedavisi tamamlanmış hastalar listesi (Günlük, son 5)
        $completedEncounters = Encounter::with('patient', 'dentist')
            ->where('status', EncounterStatus::DONE)
            ->whereDate('ended_at', today())
            ->latest('ended_at')
            ->take(5) // Sadece son 5'i al
            ->get();

        // 3. Tahsil edilmemiş faturalar (ilk 5)
        $unpaidInvoices = Invoice::with('patient')
            ->whereIn('status', [InvoiceStatus::UNPAID, InvoiceStatus::OVERDUE, InvoiceStatus::POSTPONED])
            ->orderBy('issue_date')
            ->take(5) // Sadece ilk 5'i al
            ->get();

        // 4. Acilde sıra bekleyen hastalar (ilk 5)
        $emergencyQueue = Encounter::with('patient', 'dentist')
            ->where('status', EncounterStatus::WAITING)
            ->whereIn('type', [EncounterType::EMERGENCY, EncounterType::WALK_IN])
            ->orderByRaw("CASE triage_level WHEN 'red' THEN 1 WHEN 'yellow' THEN 2 WHEN 'green' THEN 3 ELSE 4 END ASC")
            ->orderBy('arrived_at')
            ->take(5) // Sadece ilk 5'i al
            ->get();
            
        return view('dashboard', compact(
            'uncheckedAppointments',
            'completedEncounters',
            'unpaidInvoices',
            'emergencyQueue'
        ));
    }
}

