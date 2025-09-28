<?php

namespace App\Exports;

use App\Models\Appointment;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AppointmentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected Request $request;
    protected string $view;

    public function __construct(Request $request, string $view)
    {
        $this->request = $request;
        $this->view = $view;
    }

    public function collection()
    {
        // Get appointments using the same logic as the calendar view
        $user = $this->request->user();
        $isDentist = $user && $user->isDentist();

        $referenceDate = $this->request->query('goto_date')
            ? \Carbon\Carbon::parse($this->request->query('goto_date'))
            : now();

        $referenceMonth = $this->resolveReferenceMonth($this->request->query('month'));

        switch ($this->view) {
            case 'day':
                $gridStart = $referenceDate->copy()->startOfDay();
                $gridEnd = $referenceDate->copy()->endOfDay();
                break;
            case 'week':
                $gridStart = $referenceDate->copy()->startOfWeek(CarbonInterface::MONDAY);
                $gridEnd = $referenceDate->copy()->endOfWeek(CarbonInterface::SUNDAY);

                break;
            case 'month':
            default:
                $gridStart = $referenceMonth->copy()->startOfWeek(CarbonInterface::MONDAY);
                $gridEnd = $referenceMonth->copy()->endOfWeek(CarbonInterface::SUNDAY);
                break;
        }

        $selectedDentists = array_map('intval', $this->normalizeArrayQuery($this->request->query('dentists', [])));
        $selectedStatuses = $this->normalizeArrayQuery($this->request->query('statuses', []));

        if ($isDentist) {
            $selectedDentists = [$user->id];
        }

        return Appointment::with(['patient', 'dentist'])
            ->whereBetween('start_at', [$gridStart, $gridEnd])
            ->when(!empty($selectedDentists), fn($q) => $q->whereIn('dentist_id', $selectedDentists))
            ->when(!empty($selectedStatuses), fn($q) => $q->whereIn('status', $selectedStatuses))
            ->orderBy('start_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tarih',
            'Saat',
            'Hasta Adı',
            'Hasta Telefon',
            'Hekim',
            'Durum',
            'Notlar'
        ];
    }

    public function map($appointment): array
    {
        return [
            $appointment->start_at->format('d.m.Y'),
            $appointment->start_at->format('H:i') . ' - ' . $appointment->end_at->format('H:i'),
            $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
            $appointment->patient->phone_primary ?? '',
            $appointment->dentist->name,
            $appointment->status->label(),
            $appointment->notes ?? ''
        ];
    }

    private function resolveReferenceMonth(?string $month): \Carbon\Carbon
    {
        if ($month) {
            try {
                return \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
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
}