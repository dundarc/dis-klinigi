<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Encounter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // TODO: Yetki kontrolü (sadece admin görebilir) eklenecek.

        // Son 30 günün randevu sayıları (günlük)
        $dailyAppointments = Appointment::where('start_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(start_at) as date'),
                DB::raw('COUNT(*) as count')
            ]);

        // Veriyi Chart.js için formatla
        $dailyAppointmentLabels = $dailyAppointments->pluck('date');
        $dailyAppointmentData = $dailyAppointments->pluck('count');

        // Randevu durumlarına göre dağılım (son 3 ay)
        $statusCounts = Appointment::where('start_at', '>=', Carbon::now()->subMonths(3))
            ->groupBy('status')
            ->get([
                'status',
                DB::raw('COUNT(*) as count')
            ]);

        $statusLabels = $statusCounts->pluck('status')->map(fn($s) => $s->value);
        $statusData = $statusCounts->pluck('count');

        // Verileri view'e gönder
        $chartData = [
            'dailyAppointments' => [
                'labels' => $dailyAppointmentLabels,
                'data' => $dailyAppointmentData,
            ],
            'appointmentStatus' => [
                'labels' => $statusLabels,
                'data' => $statusData,
            ]
        ];

        return view('reports.index', compact('chartData'));
    }
}