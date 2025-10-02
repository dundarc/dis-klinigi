<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailLogController extends Controller
{
    /**
     * Display a listing of email logs
     */
    public function index(Request $request): View
    {
        $query = EmailLog::query();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('template_key')) {
            $query->where('template_key', $request->template_key);
        }

        if ($request->filled('to_email')) {
            $query->where('to_email', 'like', '%' . $request->to_email . '%');
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('system.email.logs.index', compact('logs'));
    }

    /**
     * Display the specified email log
     */
    public function show(EmailLog $log): View
    {
        return view('system.email.logs.show', compact('log'));
    }
}