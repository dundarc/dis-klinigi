<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\TreatmentPlanItem;
use App\Models\User;
use App\Services\AppointmentService;
use App\Exports\AppointmentsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly AppointmentService $appointmentService)
    {
    }

    public function index(Request $request): View
    {
        return $this->getCalendarView($request, 'month');
    }

    public function week(Request $request): View
    {
        return $this->getCalendarView($request, 'week');
    }

    public function day(Request $request): View
    {
        return $this->getCalendarView($request, 'day');
    }

    private function getCalendarView(Request $request, string $view): View
    {
        $this->authorize('viewAny', Appointment::class);

        $user = $request->user();
        $isDentist = $user && $user->isDentist();

        $currentView = $view;

        // --- Yeni: "goto_date" desteği ---
        $referenceDate = $request->query('goto_date')
            ? Carbon::parse($request->query('goto_date'))
            : now();

        $referenceMonth = $this->resolveReferenceMonth($request->query('month'));

        $gridStart = null;
        $gridDays = 0;

        switch ($currentView) {
            case 'day':
                $gridStart = $referenceDate->copy()->startOfDay();
                $gridDays = 1;
                break;
            case 'week':
                $gridStart = $referenceDate->copy()->startOfWeek(1); // Monday
                $gridDays = 7;
                break;
            case 'month':
            default:
                $gridStart = $referenceMonth->copy()->startOfWeek(1); // Monday
                $gridDays = 42;
                break;
        }

        $gridEnd = $gridStart->copy()->addDays($gridDays - 1)->endOfDay();

        $selectedDentists = array_map('intval', $this->normalizeArrayQuery($request->query('dentists', [])));
        $selectedStatuses = $this->normalizeArrayQuery($request->query('statuses', []));

        if ($isDentist) {
            $selectedDentists = [$user->id];
        }

        $appointments = $this->appointmentService->getAppointments(
            $gridStart->toDateTimeString(),
            $gridEnd->toDateTimeString(),
            !empty($selectedDentists) ? $selectedDentists : ($isDentist ? [$user->id] : null),
            !empty($selectedStatuses) ? $selectedStatuses : null
        );

        if ($currentView === 'month') {
            $appointmentsByDate = $appointments->groupBy(fn (Appointment $appointment) => $appointment->start_at->toDateString());

            $days = collect();
            $cursor = $gridStart->copy();
            for ($index = 0; $index < $gridDays; $index++) {
                $dateKey = $cursor->toDateString();
                $days->push([
                    'date' => $cursor->copy(),
                    'isCurrentMonth' => $cursor->month === $referenceMonth->month,
                    'isToday' => $cursor->isToday(),
                    'appointments' => $appointmentsByDate->get($dateKey, collect()),
                ]);

                $cursor->addDay();
            }
        } else {
            // For week and day views, create hour-based grid
            $hours = collect(range(8, 18)); // 8 AM to 6 PM
            $days = collect();
            $cursor = $gridStart->copy();
            for ($index = 0; $index < $gridDays; $index++) {
                $dateKey = $cursor->toDateString();
                $dayAppointments = $appointments->filter(fn (Appointment $appointment) => $appointment->start_at->toDateString() === $dateKey);

                $hoursData = $hours->map(function ($hour) use ($dayAppointments) {
                    $hourAppointments = $dayAppointments->filter(function (Appointment $appointment) use ($hour) {
                        return $appointment->start_at->hour >= $hour && $appointment->start_at->hour < $hour + 1;
                    });

                    return [
                        'hour' => $hour,
                        'label' => sprintf('%02d:00', $hour),
                        'appointments' => $hourAppointments->sortBy('start_at')->values(),
                    ];
                });

                $days->push([
                    'date' => $cursor->copy(),
                    'isCurrentMonth' => $cursor->month === $referenceMonth->month,
                    'isToday' => $cursor->isToday(),
                    'hours' => $hoursData,
                ]);

                $cursor->addDay();
            }
        }

        $locale = app()->getLocale();

        $weekDays = collect(range(0, 6))->map(function (int $offset) use ($gridStart, $locale) {
            return $gridStart->copy()->addDays($offset)->locale($locale)->isoFormat('ddd');
        });

        if ($currentView === 'month') {
            $monthLabel = $referenceMonth->copy()->locale($locale)->isoFormat('MMMM YYYY');
        } elseif ($currentView === 'week') {
            $weekStart = $referenceDate->copy()->startOfWeek(1); // Monday
            $weekEnd = $referenceDate->copy()->endOfWeek(0); // Sunday
            $monthLabel = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M Y');
        } elseif ($currentView === 'day') {
            $monthLabel = $referenceDate->copy()->locale($locale)->isoFormat('dddd, D MMMM YYYY');
        } else {
            $monthLabel = $referenceMonth->copy()->locale($locale)->isoFormat('MMMM YYYY');
        }
        $monthLabel = mb_convert_case($monthLabel, MB_CASE_TITLE, 'UTF-8');

        $baseQuery = [];
        if (!$isDentist && !empty($selectedDentists)) {
            $baseQuery['dentists'] = $selectedDentists;
        }
        if (!empty($selectedStatuses)) {
            $baseQuery['statuses'] = $selectedStatuses;
        }

        // --- Yeni: Navigasyon URL’leri ---
        $today = Carbon::today();

        $previousMonthUrl = $this->buildCalendarUrl($baseQuery, $referenceMonth->copy()->subMonth(), 'month');
        $nextMonthUrl     = $this->buildCalendarUrl($baseQuery, $referenceMonth->copy()->addMonth(), 'month');
        $todayUrl         = route('calendar.today');

        $previousWeekUrl  = route('calendar.week', array_merge($baseQuery, [
            'goto_date' => $referenceDate->copy()->subWeek()->toDateString(),
        ]));

        $nextWeekUrl = route('calendar.week', array_merge($baseQuery, [
            'goto_date' => $referenceDate->copy()->addWeek()->toDateString(),
        ]));

        $previousDayUrl = route('calendar.day', array_merge($baseQuery, [
            'goto_date' => $referenceDate->copy()->subDay()->toDateString(),
        ]));

        $nextDayUrl = route('calendar.day', array_merge($baseQuery, [
            'goto_date' => $referenceDate->copy()->addDay()->toDateString(),
        ]));

        $gotoDate = $referenceDate->toDateString();

        // --- Doktor listesi ---
        $dentistsQuery = User::where('role', 'dentist')->orderBy('name');
        if ($isDentist) {
            $dentistsQuery->where('id', $user->id);
        }
        $dentists = $dentistsQuery->get(['id', 'name']);

        $statusOptions = collect(AppointmentStatus::cases())
            ->map(fn (AppointmentStatus $status) => [
                'value' => $status->value,
                'label' => __('appointments.status.' . $status->value),
            ]);

        $statusLabels = $statusOptions->pluck('label', 'value')->all();

        $statusStyles = [
            'scheduled' => 'border-blue-500 bg-blue-50 text-blue-700 dark:border-blue-400 dark:bg-blue-900/40 dark:text-blue-100',
            'confirmed' => 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:border-emerald-400 dark:bg-emerald-900/40 dark:text-emerald-100',
            'checked_in' => 'border-amber-500 bg-amber-100 text-amber-900 dark:border-amber-400 dark:bg-amber-800/60 dark:text-amber-50',
            'in_service' => 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-900/40 dark:text-indigo-100',
            'completed' => 'border-slate-400 bg-slate-100 text-slate-700 dark:border-slate-400 dark:bg-slate-800/60 dark:text-slate-100',
            'cancelled' => 'border-rose-500 bg-rose-50 text-rose-700 dark:border-rose-400 dark:bg-rose-900/40 dark:text-rose-100',
            'no_show' => 'border-orange-500 bg-orange-50 text-orange-700 dark:border-orange-400 dark:bg-orange-900/40 dark:text-orange-100',
            'default' => 'border-gray-300 bg-gray-100 text-gray-700 dark:border-gray-600 dark:bg-gray-900/40 dark:text-gray-200',
        ];

        $viewData = [
            'title' => 'Randevular',
            'days' => $days,
            'weekDays' => $weekDays,
            'monthLabel' => $monthLabel,
            'currentMonthKey' => $referenceMonth->format('Y-m'),
            'previousMonthUrl' => $previousMonthUrl,
            'nextMonthUrl' => $nextMonthUrl,
            'todayUrl' => $todayUrl,
            'previousWeekUrl' => $previousWeekUrl,
            'nextWeekUrl' => $nextWeekUrl,
            'previousDayUrl' => $previousDayUrl,
            'nextDayUrl' => $nextDayUrl,
            'gotoDate' => $gotoDate,
            'dentists' => $dentists,
            'statusOptions' => $statusOptions,
            'statusLabels' => $statusLabels,
            'statusStyles' => $statusStyles,
            'selectedDentists' => $selectedDentists,
            'selectedStatuses' => $selectedStatuses,
            'filtersApplied' => !$isDentist && (!empty($selectedDentists) || !empty($selectedStatuses)),
            'showDentistFilter' => !$isDentist,
            'currentView' => $currentView,
        ];

        switch ($currentView) {
            case 'month':
                return view('calendar.month', $viewData);
            case 'week':
                return view('calendar.week', $viewData);
            case 'day':
                return view('calendar.day', $viewData);
            default:
                return view('calendar.month', $viewData);
        }
    }

    public function today(Request $request): View
    {
        $this->authorize('viewAny', Appointment::class);

        $user = $request->user();
        $isDentist = $user && $user->isDentist();

        $date = today();
        $selectedDentists = array_map('intval', $this->normalizeArrayQuery($request->query('dentists', [])));

        if ($isDentist) {
            $selectedDentists = [$user->id];
        }

        $appointments = $this->appointmentService->getAppointments(
            $date->copy()->startOfDay()->toDateTimeString(),
            $date->copy()->endOfDay()->toDateTimeString(),
            !empty($selectedDentists) ? $selectedDentists : ($isDentist ? [$user->id] : null),
            null
        )->sortBy(fn (Appointment $appointment) => $appointment->start_at);

        $hourlySlots = $appointments
            ->groupBy(fn (Appointment $appointment) => $appointment->start_at->format('H:00'))
            ->sortKeys()
            ->map(fn ($items, $hourLabel) => [
                'label' => $hourLabel,
                'appointments' => $items->sortBy(fn (Appointment $appointment) => $appointment->start_at)->values(),
            ])->values();

        $dentistsQuery = User::where('role', 'dentist')->orderBy('name');
        if ($isDentist) {
            $dentistsQuery->where('id', $user->id);
        }
        $dentists = $dentistsQuery->get(['id', 'name']);

        $statusLabels = collect(AppointmentStatus::cases())->mapWithKeys(fn (AppointmentStatus $status) => [
            $status->value => __('appointments.status.' . $status->value),
        ]);

        $statusStyles = [
            'scheduled' => 'border-blue-500 bg-blue-50 text-blue-700 dark:border-blue-400 dark:bg-blue-900/40 dark:text-blue-100',
            'confirmed' => 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:border-emerald-400 dark:bg-emerald-900/40 dark:text-emerald-100',
            'checked_in' => 'border-amber-500 bg-amber-100 text-amber-900 dark:border-amber-400 dark:bg-amber-800/60 dark:text-amber-50',
            'in_service' => 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-900/40 dark:text-indigo-100',
            'completed' => 'border-slate-400 bg-slate-100 text-slate-700 dark:border-slate-400 dark:bg-slate-800/60 dark:text-slate-100',
            'cancelled' => 'border-rose-500 bg-rose-50 text-rose-700 dark:border-rose-400 dark:bg-rose-900/40 dark:text-rose-100',
            'no_show' => 'border-orange-500 bg-orange-50 text-orange-700 dark:border-orange-400 dark:bg-orange-900/40 dark:text-orange-100',
            'default' => 'border-gray-300 bg-gray-100 text-gray-700 dark:border-gray-600 dark:bg-gray-900/40 dark:text-gray-200',
        ];

        $emergencyQuery = Encounter::with(['patient:id,first_name,last_name', 'dentist:id,name'])
            ->whereIn('type', [EncounterType::EMERGENCY, EncounterType::WALK_IN])
            ->where('status', EncounterStatus::WAITING)
            ->whereDate('arrived_at', $date)
            ->orderByRaw("CASE triage_level WHEN 'red' THEN 1 WHEN 'yellow' THEN 2 WHEN 'green' THEN 3 ELSE 4 END")
            ->orderBy('arrived_at');

        if ($isDentist) {
            $emergencyQuery->where('dentist_id', $user->id);
        }

        $emergencyEncounters = $emergencyQuery->get();

        $locale = app()->getLocale();
        $todayLabel = $date->copy()->locale($locale)->isoFormat('dddd, D MMMM YYYY');
        $todayLabel = mb_convert_case($todayLabel, MB_CASE_TITLE, 'UTF-8');

        return view('calendar.today', [
            'title' => 'Randevular',
            'todayLabel' => $todayLabel,
            'hourlySlots' => $hourlySlots,
            'appointments' => $appointments,
            'dentists' => $dentists,
            'statusLabels' => $statusLabels,
            'statusStyles' => $statusStyles,
            'selectedDentists' => $selectedDentists,
            'filtersApplied' => !$isDentist && !empty($selectedDentists),
            'emergencyEncounters' => $emergencyEncounters,
            'showDentistFilter' => !$isDentist,
        ]);
    }

    public function show(Appointment $appointment): View
    {
        $this->authorize('view', $appointment);

        $appointment->load(['dentist', 'patient.treatmentPlans']);

        $unplannedItems = TreatmentPlanItem::whereIn('treatment_plan_id', $appointment->patient->treatmentPlans->pluck('id'))
            ->whereNull('appointment_id')
            ->with('treatment')
            ->get();

        $user = auth()->user();
        $dentistsQuery = User::where('role', 'dentist')->orderBy('name');
        if ($user && $user->isDentist()) {
            $dentistsQuery->where('id', $user->id);
        }
        $dentists = $dentistsQuery->get(['id', 'name']);

        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $statuses = AppointmentStatus::cases();

        return view('calendar.show', [
            'title' => 'Randevu Detayı',
            'appointment' => $appointment,
            'dentists' => $dentists,
            'patients' => $patients,
            'statuses' => $statuses,
            'unplannedItems' => $unplannedItems,
            'canEdit' => $user ? $user->can('update', $appointment) : false,
        ]);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->appointmentService->updateAppointment($appointment, $request->validated());

        return redirect()->route('calendar.show', $appointment)->with('status', __('Randevu basariyla guncellendi.'));
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $this->authorize('delete', $appointment);

        $this->appointmentService->deleteAppointment($appointment);

        return redirect()->route('calendar')->with('status', __('Randevu basariyla silindi.'));
    }

    private function resolveReferenceMonth(?string $month): Carbon
    {
        if ($month) {
            try {
                return Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            } catch (\Throwable) {
                // ignore and fall back to current month
            }
        }

        return now()->startOfMonth();
    }

    private function normalizeArrayQuery(mixed $value): array
    {
        if (is_null($value)) {
            return [];
        }

        $items = is_array($value) ? $value : [$value];

        return array_values(array_filter($items, static fn ($item) => $item !== null && $item !== ''));
    }

    private function buildCalendarUrl(array $baseQuery, Carbon $target, string $view, array $extraQuery = []): string
    {
        $query = array_merge($baseQuery, $extraQuery);
        $query['month'] = $target->copy()->startOfMonth()->format('Y-m');
        $query['view'] = $view;

        return route('calendar', $query);
    }

    public function attachItems(Request $request, Appointment $appointment): RedirectResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:treatment_plan_items,id',
        ]);

        $this->authorize('update', $appointment);

        $items = TreatmentPlanItem::whereIn('id', $request->input('items'))
            ->whereNull('appointment_id')
            ->get();

        foreach ($items as $item) {
            if ($item->treatmentPlan->patient_id === $appointment->patient_id) {
                $item->update(['appointment_id' => $appointment->id]);
            }
        }

        return redirect()->route('calendar.show', $appointment)->with('status', __('Tedaviler randevuya eklendi.'));
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'pdf');
        $view = $request->query('view', 'month');

        if ($format === 'pdf') {
            // Get the same data as the view
            $data = $this->getCalendarViewData($request, $view);
            $pdf = Pdf::loadView('calendar.export', $data + ['view' => $view]);
            return $pdf->download('calendar-' . $view . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new AppointmentsExport($request, $view), 'calendar-' . $view . '.xlsx');
        }

        return redirect()->back();
    }

    private function getCalendarViewData(Request $request, string $view): array
    {
        // Simplified version of getCalendarView without view return
        $this->authorize('viewAny', Appointment::class);

        $user = $request->user();
        $isDentist = $user && $user->isDentist();

        $currentView = $view;

        $referenceDate = $request->query('goto_date')
            ? Carbon::parse($request->query('goto_date'))
            : now();

        $referenceMonth = $this->resolveReferenceMonth($request->query('month'));

        $gridStart = null;
        $gridDays = 0;

        switch ($currentView) {
            case 'day':
                $gridStart = $referenceDate->copy()->startOfDay();
                $gridDays = 1;
                break;
            case 'week':
                $gridStart = $referenceDate->copy()->startOfWeek(1); // Monday
                $gridDays = 7;
                break;
            case 'month':
            default:
                $gridStart = $referenceMonth->copy()->startOfWeek(1); // Monday
                $gridDays = 42;
                break;
        }

        $gridEnd = $gridStart->copy()->addDays($gridDays - 1)->endOfDay();

        $selectedDentists = array_map('intval', $this->normalizeArrayQuery($request->query('dentists', [])));
        $selectedStatuses = $this->normalizeArrayQuery($request->query('statuses', []));

        if ($isDentist) {
            $selectedDentists = [$user->id];
        }

        $appointments = $this->appointmentService->getAppointments(
            $gridStart->toDateTimeString(),
            $gridEnd->toDateTimeString(),
            !empty($selectedDentists) ? $selectedDentists : ($isDentist ? [$user->id] : null),
            !empty($selectedStatuses) ? $selectedStatuses : null
        );

        if ($currentView === 'month') {
            $appointmentsByDate = $appointments->groupBy(fn (Appointment $appointment) => $appointment->start_at->toDateString());

            $days = collect();
            $cursor = $gridStart->copy();
            for ($index = 0; $index < $gridDays; $index++) {
                $dateKey = $cursor->toDateString();
                $days->push([
                    'date' => $cursor->copy(),
                    'isCurrentMonth' => $cursor->month === $referenceMonth->month,
                    'isToday' => $cursor->isToday(),
                    'appointments' => $appointmentsByDate->get($dateKey, collect()),
                ]);

                $cursor->addDay();
            }
        } else {
            $hours = collect(range(8, 18));
            $days = collect();
            $cursor = $gridStart->copy();
            for ($index = 0; $index < $gridDays; $index++) {
                $dateKey = $cursor->toDateString();
                $dayAppointments = $appointments->filter(fn (Appointment $appointment) => $appointment->start_at->toDateString() === $dateKey);

                $hoursData = $hours->map(function ($hour) use ($dayAppointments) {
                    $hourAppointments = $dayAppointments->filter(function (Appointment $appointment) use ($hour) {
                        return $appointment->start_at->hour >= $hour && $appointment->start_at->hour < $hour + 1;
                    });

                    return [
                        'hour' => $hour,
                        'label' => sprintf('%02d:00', $hour),
                        'appointments' => $hourAppointments->sortBy('start_at')->values(),
                    ];
                });

                $days->push([
                    'date' => $cursor->copy(),
                    'isCurrentMonth' => $cursor->month === $referenceMonth->month,
                    'isToday' => $cursor->isToday(),
                    'hours' => $hoursData,
                ]);

                $cursor->addDay();
            }
        }

        $locale = app()->getLocale();

        $weekDays = collect(range(0, 6))->map(function (int $offset) use ($gridStart, $locale) {
            return $gridStart->copy()->addDays($offset)->locale($locale)->isoFormat('ddd');
        });

        if ($currentView === 'month') {
            $monthLabel = $referenceMonth->copy()->locale($locale)->isoFormat('MMMM YYYY');
        } elseif ($currentView === 'week') {
            $weekStart = $referenceDate->copy()->startOfWeek(1); // Monday
            $weekEnd = $referenceDate->copy()->endOfWeek(0); // Sunday
            $monthLabel = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M Y');
        } elseif ($currentView === 'day') {
            $monthLabel = $referenceDate->copy()->locale($locale)->isoFormat('dddd, D MMMM YYYY');
        } else {
            $monthLabel = $referenceMonth->copy()->locale($locale)->isoFormat('MMMM YYYY');
        }
        $monthLabel = mb_convert_case($monthLabel, MB_CASE_TITLE, 'UTF-8');

        return [
            'days' => $days,
            'weekDays' => $weekDays,
            'monthLabel' => $monthLabel,
            'currentMonthKey' => $referenceMonth->format('Y-m'),
            'dentists' => User::where('role', 'dentist')->orderBy('name')->get(['id', 'name']),
            'statusOptions' => collect(AppointmentStatus::cases())
                ->map(fn (AppointmentStatus $status) => [
                    'value' => $status->value,
                    'label' => __('appointments.status.' . $status->value),
                ]),
            'statusLabels' => collect(AppointmentStatus::cases())->mapWithKeys(fn (AppointmentStatus $status) => [
                $status->value => __('appointments.status.' . $status->value),
            ]),
            'statusStyles' => [
                'scheduled' => 'border-blue-500 bg-blue-50 text-blue-700 dark:border-blue-400 dark:bg-blue-900/40 dark:text-blue-100',
                'confirmed' => 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:border-emerald-400 dark:bg-emerald-900/40 dark:text-emerald-100',
                'checked_in' => 'border-amber-500 bg-amber-100 text-amber-900 dark:border-amber-400 dark:bg-amber-800/60 dark:text-amber-50',
                'in_service' => 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-900/40 dark:text-indigo-100',
                'completed' => 'border-slate-400 bg-slate-100 text-slate-700 dark:border-slate-400 dark:bg-slate-800/60 dark:text-slate-100',
                'cancelled' => 'border-rose-500 bg-rose-50 text-rose-700 dark:border-rose-400 dark:bg-rose-900/40 dark:text-rose-100',
                'no_show' => 'border-orange-500 bg-orange-50 text-orange-700 dark:border-orange-400 dark:bg-orange-900/40 dark:text-orange-100',
                'default' => 'border-gray-300 bg-gray-100 text-gray-700 dark:border-gray-600 dark:bg-gray-900/40 dark:text-gray-200',
            ],
            'selectedDentists' => $selectedDentists,
            'selectedStatuses' => $selectedStatuses,
            'currentView' => $currentView,
        ];
    }
}
