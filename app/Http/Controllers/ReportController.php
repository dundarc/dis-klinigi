<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Enums\UserRole;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;

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
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('DATE(paid_at) as date, SUM(grand_total) as total')
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
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('DATE(paid_at) as date, SUM(grand_total) as total')
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
                DB::raw('SUM(invoice_items.unit_price * invoice_items.qty) as total_revenue')
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
                DB::raw('SUM(invoice_items.unit_price * invoice_items.qty) as total_revenue'),
                DB::raw('AVG(invoice_items.unit_price * invoice_items.qty) as average_revenue')
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
        $dentists = User::where('role', UserRole::DENTIST)->orderBy('name')->get();

        // Randevu analiz raporunun mantığı burada olacak.
        return view('reports.appointment-analysis', [
            'dentists' => $dentists,
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

        // Note: This uses MySQL-specific DATE_FORMAT functions.
        $acquisitionData = \App\Models\Patient::query()
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

        return view('reports.new-patient-acquisition', [
            'acquisitionData' => $acquisitionData,
        ]);
    }
}
