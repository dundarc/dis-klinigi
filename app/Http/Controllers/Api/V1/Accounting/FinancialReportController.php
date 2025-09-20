<?php
namespace App\Http\Controllers\Api\V1\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Enums\InvoiceStatus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function summary(Request $request)
    {
        // Son 30 günün günlük kazançları (sadece ödenmiş faturalar)
        $dailyRevenue = Invoice::where('status', InvoiceStatus::PAID)
            ->where('issue_date', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(issue_date) as date'),
                DB::raw('SUM(grand_total) as total')
            ]);
        
        return response()->json([
            'daily_revenue_last_30_days' => [
                'labels' => $dailyRevenue->pluck('date'),
                'data' => $dailyRevenue->pluck('total'),
            ]
        ]);
    }
}