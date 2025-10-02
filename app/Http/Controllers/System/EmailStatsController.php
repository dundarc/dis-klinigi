<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\View\View;
use Carbon\Carbon;

class EmailStatsController extends Controller
{
    /**
     * Display email statistics
     */
    public function index(): View
    {
        $stats = $this->getEmailStats();

        return view('system.email.stats.index', compact('stats'));
    }

    /**
     * Get email statistics for the last 30 days
     */
    private function getEmailStats(): array
    {
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(30);

        // Daily stats for the last 30 days
        $dailyStats = EmailLog::selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        // Overall stats
        $totalSent = EmailLog::where('status', 'sent')->where('created_at', '>=', $startDate)->count();
        $totalFailed = EmailLog::where('status', 'failed')->where('created_at', '>=', $startDate)->count();
        $totalQueued = EmailLog::where('status', 'queued')->where('created_at', '>=', $startDate)->count();

        $successRate = $totalSent + $totalFailed > 0 ? round(($totalSent / ($totalSent + $totalFailed)) * 100, 2) : 0;

        $lastSentAt = EmailLog::where('status', 'sent')->max('sent_at');

        return [
            'daily_stats' => $dailyStats,
            'total_sent' => $totalSent,
            'total_failed' => $totalFailed,
            'total_queued' => $totalQueued,
            'success_rate' => $successRate,
            'last_sent_at' => $lastSentAt,
        ];
    }
}