<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Enums\InvoiceStatus;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $showCheckInCard = true;
        $showEmergencyCard = true;
        $showCompletedCard = true;
        $showUnpaidInvoicesCard = true;

        $uncheckedAppointments = collect();
        $completedEncounters = collect();
        $emergencyQueue = collect();
        $unpaidInvoices = collect();

        $today = today();

        if ($user && $user->isAccountant()) {
            $unpaidInvoices = Invoice::with('patient')
                ->whereIn('status', [InvoiceStatus::UNPAID, InvoiceStatus::OVERDUE, InvoiceStatus::POSTPONED])
                ->orderBy('issue_date')
                ->take(5)
                ->get();

            $showCheckInCard = false;
            $showEmergencyCard = false;
            $showCompletedCard = false;
        } else {
            $uncheckedQuery = Appointment::with('patient', 'dentist')
                ->whereDate('start_at', $today)
                ->whereIn('status', [AppointmentStatus::SCHEDULED, AppointmentStatus::CONFIRMED])
                ->orderBy('start_at');

            $completedQuery = Encounter::with('patient', 'dentist')
                ->where('status', EncounterStatus::DONE)
                ->whereDate('ended_at', $today)
                ->latest('ended_at');

            $emergencyQuery = Encounter::with('patient', 'dentist')
                ->where('status', EncounterStatus::WAITING)
                ->whereIn('type', [EncounterType::EMERGENCY, EncounterType::WALK_IN])
                ->orderByRaw("CASE triage_level WHEN 'red' THEN 1 WHEN 'yellow' THEN 2 WHEN 'green' THEN 3 ELSE 4 END")
                ->orderBy('arrived_at');

            if ($user && $user->isDentist()) {
                $uncheckedQuery->where('dentist_id', $user->id);
                $completedQuery->where('dentist_id', $user->id);
                $emergencyQuery->where('dentist_id', $user->id);
                $showUnpaidInvoicesCard = false;
            }

            $uncheckedAppointments = $uncheckedQuery->take(5)->get();
            $completedEncounters = $completedQuery->take(5)->get();
            $emergencyQueue = $emergencyQuery->take(5)->get();

            if (!$user || !$user->isDentist()) {
                $unpaidInvoices = Invoice::with('patient')
                    ->whereIn('status', [InvoiceStatus::UNPAID, InvoiceStatus::OVERDUE, InvoiceStatus::POSTPONED])
                    ->orderBy('issue_date')
                    ->take(5)
                    ->get();
            }
        }

        return view('dashboard', [
            'uncheckedAppointments' => $uncheckedAppointments,
            'completedEncounters' => $completedEncounters,
            'unpaidInvoices' => $unpaidInvoices,
            'emergencyQueue' => $emergencyQueue,
            'showCheckInCard' => $showCheckInCard,
            'showEmergencyCard' => $showEmergencyCard,
            'showCompletedCard' => $showCompletedCard,
            'showUnpaidInvoicesCard' => $showUnpaidInvoicesCard,
        ]);
    }
}
