<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\PatientTreatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = today();

        // Today's Appointments Stats
        $appointmentsQuery = Appointment::whereDate('start_at', $today);
        if ($user && $user->isDentist()) {
            $appointmentsQuery->where('dentist_id', $user->id);
        }
        $totalAppointments = $appointmentsQuery->count();
        $checkedInAppointments = (clone $appointmentsQuery)->where('status', AppointmentStatus::CHECKED_IN)->count();
        $cancelledAppointments = (clone $appointmentsQuery)->where('status', AppointmentStatus::CANCELLED)->count();

        // Waiting Room Stats
        $emergencyQuery = Encounter::where('status', EncounterStatus::WAITING)
            ->whereIn('type', [EncounterType::EMERGENCY, EncounterType::WALK_IN]);
        if ($user && $user->isDentist()) {
            $emergencyQuery->where('dentist_id', $user->id);
        }
        $emergencyCount = $emergencyQuery->count();
        $criticalCount = (clone $emergencyQuery)->where('triage_level', 'red')->count();

        // Check if user is admin (can access all features)
        $isAdmin = $user && $user->can('accessAdminFeatures');

        // Finance Stats - For accountants or admins
        $showFinanceCard = $isAdmin || ($user && $user->isAccountant());
        $todayCollections = $showFinanceCard ? Payment::whereDate('created_at', $today)->sum('amount') : 0;
        $unpaidInvoicesCount = $showFinanceCard ? Invoice::whereIn('status', [InvoiceStatus::UNPAID, InvoiceStatus::OVERDUE])->count() : 0;

        // Monthly Expenses - For accountants or admins
        $monthlyExpenses = $showFinanceCard ? \App\Models\Stock\StockExpense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->where('payment_status', 'paid')
            ->sum('total_amount') : 0;

        // Stock Stats - For stock managers or admins
        $showStockCard = $isAdmin || ($user && $user->can('accessStockManagement'));
        $criticalStocks = $showStockCard ? \App\Models\Stock\StockItem::with('category')
            ->whereRaw('quantity <= minimum_quantity')
            ->orderBy('quantity')
            ->take(5)
            ->get() : collect();
        $criticalStockCount = $criticalStocks->count();

        $overdueInvoices = $showStockCard ? \App\Models\Stock\StockPurchaseInvoice::overdue()
            ->with('supplier')
            ->orderBy('due_date')
            ->take(5)
            ->get() : collect();

        $recentStocks = $showStockCard ? \App\Models\Stock\StockItem::with('category')
            ->latest('created_at')
            ->take(5)
            ->get() : collect();

        $lastInvoice = $showStockCard ? Invoice::latest('created_at')->first() : null;

        // Last 5 Collections
        $lastCollections = Payment::with('invoice.patient')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'patient_name' => $payment->invoice?->patient?->first_name . ' ' . $payment->invoice?->patient?->last_name,
                    'amount' => $payment->amount,
                    'method' => $payment->method->label(),
                ];
            });

        // Last 5 Completed Procedures
        $lastProcedures = PatientTreatment::with('patient', 'encounter.dentist')
            ->whereHas('encounter', function ($q) {
                $q->where('status', EncounterStatus::DONE);
            })
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(function ($treatment) {
                return [
                    'patient_name' => $treatment->patient->first_name . ' ' . $treatment->patient->last_name,
                    'procedure_name' => $treatment->treatment->name ?? 'N/A',
                    'doctor_name' => $treatment->encounter->dentist->name ?? 'N/A',
                ];
            });

        // Last 5 Appointments
        $lastAppointments = Appointment::with('patient', 'dentist')
            ->latest('start_at')
            ->take(5)
            ->get()
            ->map(function ($appointment) {
                return [
                    'patient_name' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                    'doctor_name' => $appointment->dentist->name,
                    'time' => $appointment->start_at->format('d/m/Y H:i'),
                ];
            });

        // Calculate donut chart data for appointments
        $appointmentStats = [
            'total' => $totalAppointments,
            'checked_in' => $checkedInAppointments,
            'cancelled' => $cancelledAppointments,
            'other' => $totalAppointments - $checkedInAppointments - $cancelledAppointments,
        ];

        return view('dashboard', [
            'user' => $user,
            'appointmentStats' => $appointmentStats,
            'emergencyCount' => $emergencyCount,
            'criticalCount' => $criticalCount,
            'todayCollections' => $todayCollections,
            'unpaidInvoicesCount' => $unpaidInvoicesCount,
            'monthlyExpenses' => $monthlyExpenses,
            'criticalStocks' => $criticalStocks,
            'criticalStockCount' => $criticalStockCount,
            'overdueInvoices' => $overdueInvoices,
            'recentStocks' => $recentStocks,
            'lastInvoice' => $lastInvoice,
            'lastCollections' => $lastCollections,
            'lastProcedures' => $lastProcedures,
            'lastAppointments' => $lastAppointments,
            'showFinanceCard' => $showFinanceCard,
            'showStockCard' => $showStockCard,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Search patients
        $patients = Patient::where(function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
              ->orWhere('last_name', 'like', "%{$query}%");
        })->take(5)->get();

        foreach ($patients as $patient) {
            $results[] = [
                'type' => 'patient',
                'label' => $patient->first_name . ' ' . $patient->last_name . ' (Hasta)',
                'url' => route('patients.show', $patient->id),
            ];
        }

        // Search appointments
        $appointments = Appointment::with('patient', 'dentist')
            ->whereHas('patient', function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->orWhereHas('dentist', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->take(5)->get();

        foreach ($appointments as $appointment) {
            $results[] = [
                'type' => 'appointment',
                'label' => $appointment->dentist->name . ' - ' . $appointment->start_at->format('d/m/Y H:i') . ' (Randevu)',
                'url' => route('calendar.show', $appointment->id),
            ];
        }

        return response()->json($results);
    }

    public function quickActions()
    {
        return view('dashboard.quick-actions');
    }
}
