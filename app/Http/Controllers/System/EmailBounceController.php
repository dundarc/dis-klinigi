<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\EmailBounce;
use Illuminate\Http\Request;

class EmailBounceController extends Controller
{
    /**
     * Display a listing of bounce records.
     */
    public function index(Request $request)
    {
        $query = EmailBounce::with('emailLog')
            ->orderBy('occurred_at', 'desc');

        // Filter by bounce type
        if ($request->filled('bounce_type')) {
            $query->where('bounce_type', $request->bounce_type);
        }

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('occurred_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('occurred_at', '<=', $request->date_to);
        }

        $bounces = $query->paginate(20);

        // Statistics
        $stats = [
            'total_bounces' => EmailBounce::count(),
            'hard_bounces' => EmailBounce::where('bounce_type', 'hard')->count(),
            'soft_bounces' => EmailBounce::where('bounce_type', 'soft')->count(),
            'complaints' => EmailBounce::where('bounce_type', 'complaint')->count(),
            'recent_bounces' => EmailBounce::where('occurred_at', '>=', now()->subDays(7))->count(),
        ];

        return view('system.email.bounces.index', compact('bounces', 'stats'));
    }
}
