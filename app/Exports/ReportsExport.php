<?php

namespace App\Exports;

use App\Models\Appointment;
use App\Models\Patient;
use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportsExport implements FromCollection, WithHeadings, WithTitle
{
    protected $reportType;
    protected $filters;

    public function __construct($reportType, $filters = [])
    {
        $this->reportType = $reportType;
        $this->filters = $filters;
    }

    public function collection()
    {
        $startDate = isset($this->filters['start_date']) ? Carbon::parse($this->filters['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($this->filters['end_date']) ? Carbon::parse($this->filters['end_date'])->endOfDay() : now()->endOfMonth();

        switch ($this->reportType) {
            case 'dentist-performance':
                return $this->getDentistPerformanceData($startDate, $endDate);

            case 'treatment-revenue':
                return $this->getTreatmentRevenueData($startDate, $endDate);

            case 'appointment-analysis':
                return $this->getAppointmentAnalysisData($startDate, $endDate);

            case 'new-patient-acquisition':
                return $this->getNewPatientAcquisitionData($startDate, $endDate);

            default:
                return collect();
        }
    }

    public function headings(): array
    {
        switch ($this->reportType) {
            case 'dentist-performance':
                return ['Hekim Adı', 'Toplam Hasta', 'Toplam Tedavi', 'Toplam Gelir'];

            case 'treatment-revenue':
                return ['Tedavi Adı', 'Uygulama Sayısı', 'Toplam Gelir', 'Ortalama Gelir'];

            case 'appointment-analysis':
                return ['Hekim Adı', 'Toplam Randevu', 'Gerçekleşen', 'İptal Edilen', 'Gelmedi', 'Başarı Oranı (%)'];

            case 'new-patient-acquisition':
                return ['Dönem', 'Yeni Hasta Sayısı', 'Yaş Grubu', 'Yaş Grubu Sayısı'];

            default:
                return [];
        }
    }

    public function title(): string
    {
        $titles = [
            'dentist-performance' => 'Hekim Performans Raporu',
            'treatment-revenue' => 'Tedavi Gelir Raporu',
            'appointment-analysis' => 'Randevu Analiz Raporu',
            'new-patient-acquisition' => 'Yeni Hasta Kazanım Raporu',
        ];

        return $titles[$this->reportType] ?? 'Rapor';
    }

    private function getDentistPerformanceData($startDate, $endDate)
    {
        $query = DB::table('users')
            ->where('users.role', UserRole::DENTIST)
            ->leftJoin('patient_treatments', 'users.id', '=', 'patient_treatments.dentist_id')
            ->leftJoin('invoice_items', 'patient_treatments.id', '=', 'invoice_items.patient_treatment_id')
            ->whereBetween('patient_treatments.performed_at', [$startDate, $endDate])
            ->select(
                'users.name as dentist_name',
                DB::raw('COUNT(DISTINCT patient_treatments.patient_id) as total_patients'),
                DB::raw('COUNT(patient_treatments.id) as total_treatments'),
                DB::raw('SUM(invoice_items.unit_price * invoice_items.quantity) as total_revenue')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name');

        if (!empty($this->filters['dentist_id'])) {
            $query->where('users.id', $this->filters['dentist_id']);
        }

        return $query->get()->map(function ($item) {
            return [
                $item->dentist_name,
                $item->total_patients,
                $item->total_treatments,
                number_format($item->total_revenue ?? 0, 2, ',', '.'),
            ];
        });
    }

    private function getTreatmentRevenueData($startDate, $endDate)
    {
        return DB::table('treatments')
            ->join('patient_treatments', 'treatments.id', '=', 'patient_treatments.treatment_id')
            ->join('invoice_items', 'patient_treatments.id', '=', 'invoice_items.patient_treatment_id')
            ->whereBetween('patient_treatments.performed_at', [$startDate, $endDate])
            ->select(
                'treatments.name as treatment_name',
                DB::raw('COUNT(patient_treatments.id) as total_applications'),
                DB::raw('SUM(invoice_items.unit_price * invoice_items.quantity) as total_revenue'),
                DB::raw('AVG(invoice_items.unit_price * invoice_items.quantity) as average_revenue')
            )
            ->groupBy('treatments.id', 'treatments.name')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(function ($item) {
                return [
                    $item->treatment_name,
                    $item->total_applications,
                    number_format($item->total_revenue, 2, ',', '.'),
                    number_format($item->average_revenue, 2, ',', '.'),
                ];
            });
    }

    private function getAppointmentAnalysisData($startDate, $endDate)
    {
        $query = Appointment::whereBetween('start_at', [$startDate, $endDate])
            ->join('users', 'appointments.dentist_id', '=', 'users.id')
            ->where('users.role', UserRole::DENTIST)
            ->select(
                'users.name as dentist_name',
                DB::raw('COUNT(*) as total_appointments'),
                DB::raw('SUM(CASE WHEN appointments.status = "' . AppointmentStatus::COMPLETED->value . '" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN appointments.status = "' . AppointmentStatus::CANCELLED->value . '" THEN 1 ELSE 0 END) as cancelled'),
                DB::raw('SUM(CASE WHEN appointments.status = "' . AppointmentStatus::NO_SHOW->value . '" THEN 1 ELSE 0 END) as no_show')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name');

        if (!empty($this->filters['dentist_id'])) {
            $query->where('users.id', $this->filters['dentist_id']);
        }

        return $query->get()->map(function ($item) {
            $successRate = $item->total_appointments > 0 ? round(($item->completed / $item->total_appointments) * 100, 1) : 0;
            return [
                $item->dentist_name,
                $item->total_appointments,
                $item->completed,
                $item->cancelled,
                $item->no_show,
                $successRate,
            ];
        });
    }

    private function getNewPatientAcquisitionData($startDate, $endDate)
    {
        $period = $this->filters['period'] ?? 'daily';
        $dateFormat = match($period) {
            'monthly' => '%Y-%m',
            'weekly' => '%Y-%v',
            default => '%Y-%m-%d',
        };

        $acquisitionData = Patient::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"), DB::raw('COUNT(*) as count'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        $acquisitionData->transform(function ($item) use ($period) {
            $item->period_formatted = match($period) {
                'monthly' => Carbon::createFromFormat('Y-m', $item->period)->translatedFormat('F Y'),
                'weekly' => Carbon::now()->setISODate(substr($item->period, 0, 4), substr($item->period, 5, 2))->startOfWeek()->translatedFormat('d M Y') . ' Haftası',
                default => Carbon::createFromFormat('Y-m-d', $item->period)->translatedFormat('d F Y'),
            };
            return $item;
        });

        $ageGroupData = Patient::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('birth_date')
            ->selectRaw("
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 0 AND 12 THEN '0-12 Yaş'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 13 AND 25 THEN '13-25 Yaş'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 26 AND 40 THEN '26-40 Yaş'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) > 40 THEN '41+ Yaş'
                    ELSE 'Yaş Bilinmiyor'
                END as age_group,
                COUNT(*) as count
            ")
            ->groupBy('age_group')
            ->get();

        $result = collect();

        // Add acquisition data
        foreach ($acquisitionData as $data) {
            $result->push([
                $data->period_formatted,
                $data->count,
                '',
                '',
            ]);
        }

        // Add age group data
        foreach ($ageGroupData as $data) {
            $result->push([
                'Yaş Grubu',
                '',
                $data->age_group,
                $data->count,
            ]);
        }

        return $result;
    }
}