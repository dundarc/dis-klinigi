<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
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

    /**
     * Takvim sayfasını, filtreler için gerekli verilerle birlikte görüntüler.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Appointment::class);

        $referenceMonth = $this->resolveReferenceMonth($request->query('month'));
        $gridStart = $referenceMonth->copy()->startOfWeek(Carbon::MONDAY);
        $gridDays = 42;
        $gridEnd = $gridStart->copy()->addDays($gridDays - 1)->endOfDay();

        $selectedDentists = array_map('intval', $this->normalizeArrayQuery($request->query('dentists', [])));
        $selectedStatuses = $this->normalizeArrayQuery($request->query('statuses', []));

        $appointments = $this->appointmentService->getAppointments(
            $gridStart->toDateTimeString(),
            $gridEnd->toDateTimeString(),
            !empty($selectedDentists) ? $selectedDentists : null,
            !empty($selectedStatuses) ? $selectedStatuses : null
        );

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

        $locale = app()->getLocale();

        $weekDays = collect(range(0, 6))->map(function (int $offset) use ($gridStart, $locale) {
            return $gridStart->copy()->addDays($offset)->locale($locale)->isoFormat('ddd');
        });

        $monthLabel = $referenceMonth->copy()->locale($locale)->isoFormat('MMMM YYYY');
        $monthLabel = mb_convert_case($monthLabel, MB_CASE_TITLE, 'UTF-8');

        $baseQuery = [];
        if (!empty($selectedDentists)) {
            $baseQuery['dentists'] = $selectedDentists;
        }
        if (!empty($selectedStatuses)) {
            $baseQuery['statuses'] = $selectedStatuses;
        }

        $previousMonthUrl = $this->buildMonthUrl($baseQuery, $referenceMonth->copy()->subMonth());
        $nextMonthUrl = $this->buildMonthUrl($baseQuery, $referenceMonth->copy()->addMonth());
        $todayUrl = $this->buildMonthUrl($baseQuery, now()->startOfMonth());

        $dentists = User::where('role', UserRole::DENTIST)
            ->orderBy('name')
            ->get(['id', 'name']);

        $statusOptions = collect(AppointmentStatus::cases())
            ->map(fn (AppointmentStatus $status) => [
                'value' => $status->value,
                'label' => mb_convert_case(str_replace('_', ' ', $status->value), MB_CASE_TITLE, 'UTF-8'),
            ]);

        $statusLabels = $statusOptions->pluck('label', 'value')->all();

        $statusStyles = [
            'scheduled' => 'border-blue-500 bg-blue-50 text-blue-700 dark:border-blue-400 dark:bg-blue-900/40 dark:text-blue-100',
            'confirmed' => 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:border-emerald-400 dark:bg-emerald-900/40 dark:text-emerald-100',
            'checked_in' => 'border-amber-500 bg-amber-50 text-amber-700 dark:border-amber-400 dark:bg-amber-900/40 dark:text-amber-100',
            'in_service' => 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-900/40 dark:text-indigo-100',
            'completed' => 'border-slate-400 bg-slate-100 text-slate-700 dark:border-slate-400 dark:bg-slate-800/60 dark:text-slate-100',
            'cancelled' => 'border-rose-500 bg-rose-50 text-rose-700 dark:border-rose-400 dark:bg-rose-900/40 dark:text-rose-100',
            'no_show' => 'border-orange-500 bg-orange-50 text-orange-700 dark:border-orange-400 dark:bg-orange-900/40 dark:text-orange-100',
            'default' => 'border-gray-300 bg-gray-100 text-gray-700 dark:border-gray-600 dark:bg-gray-900/40 dark:text-gray-200',
        ];

        return view('calendar.index', [
            'days' => $days,
            'weekDays' => $weekDays,
            'monthLabel' => $monthLabel,
            'currentMonthKey' => $referenceMonth->format('Y-m'),
            'previousMonthUrl' => $previousMonthUrl,
            'nextMonthUrl' => $nextMonthUrl,
            'todayUrl' => $todayUrl,
            'dentists' => $dentists,
            'statusOptions' => $statusOptions,
            'statusLabels' => $statusLabels,
            'statusStyles' => $statusStyles,
            'selectedDentists' => $selectedDentists,
            'selectedStatuses' => $selectedStatuses,
            'filtersApplied' => !empty($selectedDentists) || !empty($selectedStatuses),
        ]);
    }

    /**
     * Belirli bir randevu için detay sayfasını gösterir.
     */
    public function show(Appointment $appointment): View
    {
        $this->authorize('view', $appointment);

        $appointment->load(['dentist', 'patient']);

        $dentists = User::where('role', UserRole::DENTIST)
            ->orderBy('name')
            ->get(['id', 'name']);

        $patients = Patient::orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        $statuses = AppointmentStatus::cases();

        return view('calendar.show', [
            'appointment' => $appointment,
            'dentists' => $dentists,
            'patients' => $patients,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Randevuyu günceller.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->appointmentService->updateAppointment($appointment, $request->validated());

        return redirect()
            ->route('calendar.show', $appointment)
            ->with('status', __('Randevu başarıyla güncellendi.'));
    }

    /**
     * Randevuyu siler.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        $this->authorize('delete', $appointment);

        $this->appointmentService->deleteAppointment($appointment);

        return redirect()
            ->route('calendar')
            ->with('status', __('Randevu başarıyla silindi.'));
    }

    private function resolveReferenceMonth(?string $month): Carbon
    {
        if ($month) {
            try {
                return Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            } catch (\Throwable) {
                // fall through to current month
            }
        }

        return now()->startOfMonth();
    }

    /**
     * @param mixed $value
     * @return array<int, string>
     */
    private function normalizeArrayQuery(mixed $value): array
    {
        if (is_null($value)) {
            return [];
        }

        $items = is_array($value) ? $value : [$value];

        return array_values(array_filter($items, static fn ($item) => $item !== null && $item !== ''));
    }

    private function buildMonthUrl(array $baseQuery, Carbon $target): string
    {
        return route('calendar', $baseQuery + ['month' => $target->format('Y-m')]);
    }
}