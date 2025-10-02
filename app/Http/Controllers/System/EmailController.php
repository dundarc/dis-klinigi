<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailSettingRequest;
use App\Models\EmailSetting;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EmailController extends Controller
{
    /**
     * Display email module dashboard
     */
    public function dashboard(): View
    {
        // Get some basic stats for the dashboard
        $totalTemplates = \App\Models\EmailTemplate::count();
        $activeTemplates = \App\Models\EmailTemplate::where('is_active', true)->count();
        $totalLogs = \App\Models\EmailLog::count();
        $recentLogs = \App\Models\EmailLog::latest()->take(5)->get();
        $bounceCount = \App\Models\EmailBounce::count();

        return view('system.email.dashboard', compact(
            'totalTemplates',
            'activeTemplates',
            'totalLogs',
            'recentLogs',
            'bounceCount'
        ));
    }

    /**
     * Display email settings form
     */
    public function index(): View
    {
        $settings = EmailSetting::getSettings();

        return view('system.email.configure', compact('settings'));
    }

    /**
     * Update email settings
     */
    public function update(EmailSettingRequest $request)
    {
        try {
            EmailSetting::updateSettings($request->validated());

            return redirect()->back()
                ->with('success', 'E-posta ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'E-posta ayarları güncellenirken bir hata oluştu: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Send test email
     */
    public function test(Request $request): JsonResponse
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $settings = EmailSetting::getSettings();

            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'message' => 'E-posta ayarları bulunamadı. Lütfen önce ayarları kaydediniz.'
                ], 400);
            }

            // Send test email
            EmailService::queue(
                $request->test_email,
                null,
                'Test E-postası - Klinik Sistemi',
                '<h1>Test E-postası</h1><p>Bu bir test e-postasıdır. E-posta ayarlarınız doğru çalışıyor!</p>',
                'Bu bir test e-postasıdır. E-posta ayarlarınız doğru çalışıyor!',
                null,
                []
            );

            return response()->json([
                'success' => true,
                'message' => 'Test e-postası başarıyla kuyruğa alındı!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test e-postası gönderilirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
