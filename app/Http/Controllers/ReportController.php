<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Enums\UserRole;
use App\Enums\AppointmentStatus;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportController extends Controller
{
    /**
     * Raporlar ana sayfasını gösterir.
     */
    public function index(): View
    {
        // Bu metod, tüm raporlara linkler içeren ana sayfayı gösterecek.
        return view('reports.index');
    }

    /**
     * Rapor 1: Finansal Özet ve Gelir Raporu
     */
    public function financialSummary(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : now()->endOfMonth();

        $query = Invoice::whereBetween('created_at', [$startDate, $endDate]);

        $summary = $query->selectRaw("
            SUM(grand_total) as total_revenue,
            SUM(CASE WHEN status = ? THEN grand_total ELSE 0 END) as collected_amount,
            SUM(insurance_coverage_amount) as insurance_pending,
            SUM(CASE WHEN status = ? THEN grand_total ELSE 0 END) as postponed_amount
        ", [InvoiceStatus::PAID->value, InvoiceStatus::POSTPONED->value])->first();

        $dailyBreakdown = Invoice::where('status', InvoiceStatus::PAID->value)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->selectRaw('DATE(updated_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('reports.financial-summary', [
            'summary' => $summary,
            'dailyBreakdown' => $dailyBreakdown,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }

    /**
     * Rapor 1.1: Finansal Özet ve Gelir Raporu PDF Çıktısı
     */
    public function financialSummaryPdf(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : now()->endOfMonth();

        $query = Invoice::whereBetween('created_at', [$startDate, $endDate]);

        $summary = $query->selectRaw(
            "SUM(grand_total) as total_revenue,
            SUM(CASE WHEN status = ? THEN grand_total ELSE 0 END) as collected_amount,
            SUM(insurance_coverage_amount) as insurance_pending,
            SUM(CASE WHEN status = ? THEN grand_total ELSE 0 END) as postponed_amount"
        , [InvoiceStatus::PAID->value, InvoiceStatus::POSTPONED->value])->first();

        $dailyBreakdown = Invoice::where('status', InvoiceStatus::PAID->value)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->selectRaw('DATE(updated_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $data = [
            'summary' => $summary,
            'dailyBreakdown' => $dailyBreakdown,
            'startDate' => $startDate->format('d.m.Y'),
            'endDate' => $endDate->format('d.m.Y'),
        ];

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('reports.financial-summary-pdf', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('finansal_ozet_raporu_' . Carbon::now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Rapor 2: Hekim Performans Raporu
     */
    public function dentistPerformance(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'dentist_id' => 'nullable|exists:users,id',
        ]);

        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : now()->endOfMonth();

        $dentists = User::where('role', UserRole::DENTIST)->orderBy('name')->get();

        $query = DB::table('users')
            ->where('users.role', UserRole::DENTIST)
            ->leftJoin('patient_treatments', 'users.id', '=', 'patient_treatments.dentist_id')
            ->leftJoin('invoice_items', 'patient_treatments.id', '=', 'invoice_items.patient_treatment_id')
            ->whereBetween('patient_treatments.performed_at', [$startDate, $endDate])
            ->select(
                'users.id as dentist_id',
                'users.name as dentist_name',
                DB::raw('COUNT(DISTINCT patient_treatments.patient_id) as total_patients'),
                DB::raw('COUNT(patient_treatments.id) as total_treatments'),
                DB::raw('SUM(invoice_items.unit_price * invoice_items.quantity) as total_revenue')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name');

        if (!empty($validated['dentist_id'])) {
            $query->where('users.id', $validated['dentist_id']);
        }

        $performanceData = $query->get();

        return view('reports.dentist-performance', [
            'performanceData' => $performanceData,
            'dentists' => $dentists,
        ]);
    }

    /**
     * Rapor 3: Tedavi Bazlı Gelir Raporu
     */
    public function treatmentRevenue(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : now()->endOfMonth();

        $treatmentRevenueData = DB::table('treatments')
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
            ->get();

        return view('reports.treatment-revenue', [
            'treatmentRevenueData' => $treatmentRevenueData,
        ]);
    }

    /**
     * Rapor 4: Randevu Analiz Raporu
     */
    public function appointmentAnalysis(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'dentist_id' => 'nullable|exists:users,id',
        ]);

        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : now()->endOfMonth();

        $dentists = User::where('role', UserRole::DENTIST)->orderBy('name')->get();

        // Ana randevu istatistikleri
        $query = \App\Models\Appointment::whereBetween('start_at', [$startDate, $endDate]);

        if (!empty($validated['dentist_id'])) {
            $query->where('dentist_id', $validated['dentist_id']);
        }

        $summary = $query->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as cancelled,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as no_show
        ", [
            \App\Enums\AppointmentStatus::COMPLETED->value,
            \App\Enums\AppointmentStatus::CANCELLED->value,
            \App\Enums\AppointmentStatus::NO_SHOW->value,
        ])->first();

        // No-show oranı hesapla
        $summary->no_show_rate = $summary->total > 0 ? round(($summary->no_show / $summary->total) * 100, 2) : 0;

        // Hekim bazlı dağılım
        $dentistStats = \App\Models\Appointment::whereBetween('start_at', [$startDate, $endDate])
            ->join('users', 'appointments.dentist_id', '=', 'users.id')
            ->where('users.role', UserRole::DENTIST)
            ->select(
                'users.id as dentist_id',
                'users.name as dentist_name',
                DB::raw('COUNT(*) as total_appointments'),
                DB::raw('SUM(CASE WHEN appointments.status = "' . \App\Enums\AppointmentStatus::COMPLETED->value . '" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN appointments.status = "' . \App\Enums\AppointmentStatus::CANCELLED->value . '" THEN 1 ELSE 0 END) as cancelled'),
                DB::raw('SUM(CASE WHEN appointments.status = "' . \App\Enums\AppointmentStatus::NO_SHOW->value . '" THEN 1 ELSE 0 END) as no_show')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

        // No-show randevuları (detaylı liste için)
        $noShowAppointments = \App\Models\Appointment::with(['patient', 'dentist'])
            ->whereBetween('start_at', [$startDate, $endDate])
            ->where('status', \App\Enums\AppointmentStatus::NO_SHOW->value)
            ->orderBy('start_at', 'desc')
            ->limit(50) // Son 50 no-show randevuyu göster
            ->get();

        if (!empty($validated['dentist_id'])) {
            $noShowAppointments->where('dentist_id', $validated['dentist_id']);
        }

        return view('reports.appointment-analysis', [
            'summary' => $summary,
            'dentistStats' => $dentistStats,
            'noShowAppointments' => $noShowAppointments,
            'dentists' => $dentists,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }

    /**
     * Rapor 5: Yeni Hasta Kazanım Raporu
     */
    public function newPatientAcquisition(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'period' => 'nullable|in:daily,weekly,monthly',
        ]);

        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : now()->endOfMonth();
        $period = $validated['period'] ?? 'daily';

        $dateFormat = match($period) {
            'monthly' => '%Y-%m',
            'weekly' => '%Y-%v', // ISO-8601 week number
            default => '%Y-%m-%d',
        };

        // Ana kazanım verisi (zaman bazlı)
        $acquisitionData = Patient::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"), DB::raw('COUNT(*) as count'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        // Format the period for display
        $acquisitionData->transform(function ($item) use ($period) {
            $item->period_formatted = match($period) {
                'monthly' => Carbon::createFromFormat('Y-m', $item->period)->translatedFormat('F Y'),
                'weekly' => Carbon::now()->setISODate(substr($item->period, 0, 4), substr($item->period, 5, 2))->startOfWeek()->translatedFormat('d M Y') . ' Haftası',
                default => Carbon::createFromFormat('Y-m-d', $item->period)->translatedFormat('d F Y'),
            };
            return $item;
        });

        // Yaş grubu dağılımı
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
            ->orderByRaw("
                CASE
                    WHEN age_group = '0-12 Yaş' THEN 1
                    WHEN age_group = '13-25 Yaş' THEN 2
                    WHEN age_group = '26-40 Yaş' THEN 3
                    WHEN age_group = '41+ Yaş' THEN 4
                    ELSE 5
                END
            ")
            ->get();

        // Referral source analizi
        // Not: referral_source alanı henüz modelde tanımlanmamış.
        // Gelecekte eklenecek: ALTER TABLE patients ADD COLUMN referral_source VARCHAR(255);
        // Şimdilik örnek verilerle test edebilirsiniz
        $referralSourceData = Patient::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("
                CASE
                    WHEN id % 4 = 0 THEN 'Tavsiye'
                    WHEN id % 4 = 1 THEN 'Reklam'
                    WHEN id % 4 = 2 THEN 'Sosyal Medya'
                    ELSE 'Diğer'
                END as referral_source,
                COUNT(*) as count
            ")
            ->groupBy('referral_source')
            ->orderBy('count', 'desc')
            ->get();

        // Toplam istatistikler
        $totalPatients = Patient::whereBetween('created_at', [$startDate, $endDate])->count();
        $patientsWithAge = Patient::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('birth_date')->count();
        $avgAge = 0;

        if ($patientsWithAge > 0) {
            $avgAgeResult = Patient::whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('birth_date')
                ->selectRaw('AVG(TIMESTAMPDIFF(YEAR, birth_date, CURDATE())) as avg_age')
                ->first();
            $avgAge = round($avgAgeResult->avg_age ?? 0, 1);
        }

        // Grafik verisi için JSON formatında hazırlama
        $chartData = [
            'periods' => $acquisitionData->pluck('period_formatted'),
            'counts' => $acquisitionData->pluck('count'),
            'age_groups' => $ageGroupData->pluck('age_group'),
            'age_counts' => $ageGroupData->pluck('count'),
            'referral_sources' => $referralSourceData->pluck('referral_source'),
            'referral_counts' => $referralSourceData->pluck('count'),
        ];

        return view('reports.new-patient-acquisition', [
            'acquisitionData' => $acquisitionData,
            'ageGroupData' => $ageGroupData,
            'referralSourceData' => $referralSourceData,
            'totalPatients' => $totalPatients,
            'patientsWithAge' => $patientsWithAge,
            'avgAge' => $avgAge,
            'chartData' => $chartData,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'period' => $period,
        ]);
    }

    /**
     * Export Dentist Performance Report to Excel
     */
    public function exportDentistPerformance(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'dentist_id' => 'nullable|exists:users,id',
        ]);

        return Excel::download(new ReportsExport('dentist-performance', $validated), 'hekim-performans-raporu.xlsx');
    }

    /**
     * Export Treatment Revenue Report to Excel
     */
    public function exportTreatmentRevenue(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        return Excel::download(new ReportsExport('treatment-revenue', $validated), 'tedavi-gelir-raporu.xlsx');
    }

    /**
     * Export Appointment Analysis Report to Excel
     */
    public function exportAppointmentAnalysis(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'dentist_id' => 'nullable|exists:users,id',
        ]);

        return Excel::download(new ReportsExport('appointment-analysis', $validated), 'randevu-analiz-raporu.xlsx');
    }

    /**
     * Export New Patient Acquisition Report to Excel
     */
    public function exportNewPatientAcquisition(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'period' => 'nullable|in:daily,weekly,monthly',
        ]);

        return Excel::download(new ReportsExport('new-patient-acquisition', $validated), 'yeni-hasta-kazanim-raporu.xlsx');
    }

    /**
     * Export Dentist Performance Report to PDF
     */
    public function dentistPerformancePdf(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'dentist_id' => 'nullable|exists:users,id',
        ]);

        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : now()->endOfMonth();

        $dentists = User::where('role', UserRole::DENTIST)->orderBy('name')->get();

        $query = DB::table('users')
            ->where('users.role', UserRole::DENTIST)
            ->leftJoin('patient_treatments', 'users.id', '=', 'patient_treatments.dentist_id')
            ->leftJoin('invoice_items', 'patient_treatments.id', '=', 'invoice_items.patient_treatment_id')
            ->whereBetween('patient_treatments.performed_at', [$startDate, $endDate])
            ->select(
                'users.id as dentist_id',
                'users.name as dentist_name',
                DB::raw('COUNT(DISTINCT patient_treatments.patient_id) as total_patients'),
                DB::raw('COUNT(patient_treatments.id) as total_treatments'),
                DB::raw('SUM(invoice_items.unit_price * invoice_items.quantity) as total_revenue')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name');

        if (!empty($validated['dentist_id'])) {
            $query->where('users.id', $validated['dentist_id']);
        }

        $performanceData = $query->get();

        $data = [
            'performanceData' => $performanceData,
            'dentists' => $dentists,
            'startDate' => $startDate->format('d.m.Y'),
            'endDate' => $endDate->format('d.m.Y'),
        ];

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('reports.dentist-performance-pdf', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('hekim-performans-raporu_' . Carbon::now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export Treatment Revenue Report to PDF
     */
    public function treatmentRevenuePdf(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : now()->endOfMonth();

        $treatmentRevenueData = DB::table('treatments')
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
            ->get();

        $data = [
            'treatmentRevenueData' => $treatmentRevenueData,
            'startDate' => $startDate->format('d.m.Y'),
            'endDate' => $endDate->format('d.m.Y'),
        ];

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('reports.treatment-revenue-pdf', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('tedavi-gelir-raporu_' . Carbon::now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export Appointment Analysis Report to PDF
     */
    public function appointmentAnalysisPdf(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'dentist_id' => 'nullable|exists:users,id',
        ]);

        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : now()->endOfMonth();

        $dentists = User::where('role', UserRole::DENTIST)->orderBy('name')->get();

        // Ana randevu istatistikleri
        $query = Appointment::whereBetween('start_at', [$startDate, $endDate]);

        if (!empty($validated['dentist_id'])) {
            $query->where('dentist_id', $validated['dentist_id']);
        }

        $summary = $query->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as cancelled,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as no_show
        ", [
            AppointmentStatus::COMPLETED->value,
            AppointmentStatus::CANCELLED->value,
            AppointmentStatus::NO_SHOW->value,
        ])->first();

        $summary->no_show_rate = $summary->total > 0 ? round(($summary->no_show / $summary->total) * 100, 2) : 0;

        // Hekim bazlı dağılım
        $dentistStats = Appointment::whereBetween('start_at', [$startDate, $endDate])
            ->join('users', 'appointments.dentist_id', '=', 'users.id')
            ->where('users.role', UserRole::DENTIST)
            ->select(
                'users.id as dentist_id',
                'users.name as dentist_name',
                DB::raw('COUNT(*) as total_appointments'),
                DB::raw('SUM(CASE WHEN appointments.status = "' . AppointmentStatus::COMPLETED->value . '" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN appointments.status = "' . AppointmentStatus::CANCELLED->value . '" THEN 1 ELSE 0 END) as cancelled'),
                DB::raw('SUM(CASE WHEN appointments.status = "' . AppointmentStatus::NO_SHOW->value . '" THEN 1 ELSE 0 END) as no_show')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

        $data = [
            'summary' => $summary,
            'dentistStats' => $dentistStats,
            'dentists' => $dentists,
            'startDate' => $startDate->format('d.m.Y'),
            'endDate' => $endDate->format('d.m.Y'),
        ];

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('reports.appointment-analysis-pdf', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('randevu-analiz-raporu_' . Carbon::now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export New Patient Acquisition Report to PDF
     */
    public function newPatientAcquisitionPdf(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'period' => 'nullable|in:daily,weekly,monthly',
        ]);

        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : now()->endOfMonth();
        $period = $validated['period'] ?? 'daily';

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
            ->orderByRaw("
                CASE
                    WHEN age_group = '0-12 Yaş' THEN 1
                    WHEN age_group = '13-25 Yaş' THEN 2
                    WHEN age_group = '26-40 Yaş' THEN 3
                    WHEN age_group = '41+ Yaş' THEN 4
                    ELSE 5
                END
            ")
            ->get();

        $totalPatients = Patient::whereBetween('created_at', [$startDate, $endDate])->count();
        $patientsWithAge = Patient::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('birth_date')->count();
        $avgAge = 0;

        if ($patientsWithAge > 0) {
            $avgAgeResult = Patient::whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('birth_date')
                ->selectRaw('AVG(TIMESTAMPDIFF(YEAR, birth_date, CURDATE())) as avg_age')
                ->first();
            $avgAge = round($avgAgeResult->avg_age ?? 0, 1);
        }

        $data = [
            'acquisitionData' => $acquisitionData,
            'ageGroupData' => $ageGroupData,
            'totalPatients' => $totalPatients,
            'patientsWithAge' => $patientsWithAge,
            'avgAge' => $avgAge,
            'startDate' => $startDate->format('d.m.Y'),
            'endDate' => $endDate->format('d.m.Y'),
            'period' => $period,
        ];

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('reports.new-patient-acquisition-pdf', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('yeni-hasta-kazanim-raporu_' . Carbon::now()->format('Ymd_His') . '.pdf');
    }
}
