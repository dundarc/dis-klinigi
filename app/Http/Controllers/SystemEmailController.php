<?php

namespace App\Http\Controllers;

use App\Models\EmailSetting;
use App\Services\DynamicMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SystemEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:accessAdminFeatures');
    }

    /**
     * Display email settings form
     */
    public function index()
    {
        $settings = EmailSetting::getSettings();

        return view('system.email.index', compact('settings'));
    }

    /**
     * Update email settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mailer' => 'required|string|in:smtp,mailgun,ses,postmark,sendmail',
            'host' => 'required_if:mailer,smtp|string',
            'port' => 'required_if:mailer,smtp|integer|min:1|max:65535',
            'username' => 'required_if:mailer,smtp|string',
            'password' => 'required_if:mailer,smtp|string',
            'encryption' => 'required_if:mailer,smtp|string|in:tls,ssl',
            'from_address' => 'required|email',
            'from_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            EmailSetting::updateSettings($request->only([
                'mailer', 'host', 'port', 'username', 'password',
                'encryption', 'from_address', 'from_name'
            ]));

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
    public function test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Geçerli bir e-posta adresi giriniz.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $settings = EmailSetting::getSettings();

            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'message' => 'E-posta ayarları bulunamadı. Lütfen önce ayarları kaydediniz.'
                ], 400);
            }

            // Send test email using dynamic mail service
            DynamicMailService::raw('Bu bir test e-postasıdır. E-posta ayarlarınız doğru çalışıyor!', function ($message) use ($request, $settings) {
                $message->to($request->test_email)
                        ->subject('Test E-postası - Klinik Sistemi')
                        ->from($settings->from_address, $settings->from_name);
            });

            return response()->json([
                'success' => true,
                'message' => 'Test e-postası başarıyla gönderildi!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test e-postası gönderilirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}