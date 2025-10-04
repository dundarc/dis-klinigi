<?php

namespace App\Http\Controllers;

use App\Models\EmailAutomationSetting;
use App\Models\EmailSetting;
use App\Models\Setting;
use App\Services\DynamicMailService;
use App\Services\AutomaticEmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SystemEmailController extends Controller
{
    public function __construct(private readonly AutomaticEmailService $automaticEmailService)
    {
        $this->middleware('can:accessAdminFeatures');
    }

    /**
     * Display email settings form
     */
    public function index(): View
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
            'skip_ssl_verification' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only([
                'mailer', 'host', 'port', 'username', 'password',
                'encryption', 'from_address', 'from_name',
            ]);
            $data['skip_ssl_verification'] = $request->boolean('skip_ssl_verification');

            EmailSetting::updateSettings($data);
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

    /**
     * Display automatic email settings page
     */
    public function automatic(): View
    {
        $settings = EmailAutomationSetting::getSettings();

        if (!$settings) {
            $settings = new EmailAutomationSetting([
                'patient_checkin_to_dentist' => false,
                'emergency_patient_to_dentist' => false,
                'kvkk_consent_to_admin' => false,
            ]);
        }

        return view('system.email.automatic', compact('settings'));
    }

    /**
     * Update automatic email settings
     */
    public function updateAutomatic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_checkin_to_dentist' => 'nullable|boolean',
            'emergency_patient_to_dentist' => 'nullable|boolean',
            'kvkk_consent_to_admin' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'patient_checkin_to_dentist' => $request->boolean('patient_checkin_to_dentist'),
                'emergency_patient_to_dentist' => $request->boolean('emergency_patient_to_dentist'),
                'kvkk_consent_to_admin' => $request->boolean('kvkk_consent_to_admin'),
            ];

            EmailAutomationSetting::updateSettings($data);

            return redirect()->back()
                ->with('success', 'Otomatik e-posta ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ayarlar güncellenirken bir hata oluştu: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Send test email for patient check-in automation
     */
    public function testPatientCheckin(Request $request): JsonResponse
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $testEmail = trim($request->test_email);

        \Log::info('TEST EMAIL CONTROLLER: Received test email request', [
            'raw_test_email' => $request->test_email,
            'trimmed_test_email' => $testEmail,
            'all_request_data' => $request->all(),
        ]);

        // ADDITIONAL VALIDATION: Ensure test email is valid
        $isValidEmail = filter_var($testEmail, FILTER_VALIDATE_EMAIL) !== false;
        $hasAtSymbol = strpos($testEmail, '@') !== false;
        $hasSpaces = preg_match('/\s/', $testEmail);

        \Log::info('TEST EMAIL VALIDATION', [
            'email' => $testEmail,
            'is_valid_email' => $isValidEmail,
            'has_at_symbol' => $hasAtSymbol,
            'has_spaces' => $hasSpaces,
            'should_block' => !$isValidEmail || !$hasAtSymbol || $hasSpaces,
        ]);

        if (!$isValidEmail || !$hasAtSymbol || $hasSpaces) {
            \Log::error('BLOCKING INVALID TEST EMAIL', [
                'provided_email' => $testEmail,
                'validation_failed' => true,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Geçersiz test e-posta adresi: ' . $testEmail,
            ], 400);
        }

        // Create a mock user object with the validated test email
        $mockUser = (object) [
            'email' => $testEmail,
            'name' => 'Test Kullanıcısı',
        ];

        // DOUBLE-CHECK: Ensure mock user email is still valid
        if (!filter_var($mockUser->email, FILTER_VALIDATE_EMAIL) ||
            strpos($mockUser->email, '@') === false ||
            preg_match('/\s/', $mockUser->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Mock user e-posta validasyonu başarısız: ' . $mockUser->email,
            ], 500);
        }

        // DOUBLE-CHECK: Ensure mock user email is still valid
        if (!filter_var($mockUser->email, FILTER_VALIDATE_EMAIL) ||
            strpos($mockUser->email, '@') === false ||
            preg_match('/\s/', $mockUser->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Mock user e-posta validasyonu başarısız: ' . $mockUser->email,
            ], 500);
        }

        // DOUBLE-CHECK: Ensure mock user email is still valid
        if (!filter_var($mockUser->email, FILTER_VALIDATE_EMAIL) ||
            strpos($mockUser->email, '@') === false ||
            preg_match('/\s/', $mockUser->email)) {
            \Log::critical('MOCK USER EMAIL VALIDATION FAILED', [
                'mock_email' => $mockUser->email,
                'original_test_email' => $testEmail,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Mock user e-posta validasyonu başarısız: ' . $mockUser->email,
            ], 500);
        }

        try {
            $log = $this->automaticEmailService->sendPatientCheckinTest($mockUser);

            if (!$log || $log->status === 'failed') {
                $errorMessage = $log?->error_message ?? null;

                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                        ? 'Test e-postası gönderilemedi: ' . $errorMessage
                        : 'Test e-postası gönderilemedi. Lütfen e-posta ayarlarınızı kontrol edin.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Test e-postası başarıyla gönderildi.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test e-postası gönderilirken hata oluştu: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send test email for emergency patient automation
     */
    public function testEmergencyPatient(Request $request): JsonResponse
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $testEmail = trim($request->test_email);

        // ADDITIONAL VALIDATION: Ensure test email is valid
        if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL) ||
            strpos($testEmail, '@') === false ||
            preg_match('/\s/', $testEmail)) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz test e-posta adresi: ' . $testEmail,
            ], 400);
        }

        // Create a mock user object with the validated test email
        $mockUser = (object) [
            'email' => $testEmail,
            'name' => 'Test Kullanıcısı',
        ];

        try {
            $log = $this->automaticEmailService->sendEmergencyPatientTest($mockUser);

            if (!$log || $log->status === 'failed') {
                $errorMessage = $log?->error_message ?? null;

                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                        ? 'Test e-postası gönderilemedi: ' . $errorMessage
                        : 'Test e-postası gönderilemedi. Lütfen e-posta ayarlarınızı kontrol edin.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Test e-postası başarıyla gönderildi.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test e-postası gönderilirken hata oluştu: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send test email for KVKK consent automation
     */
    public function testKvkkConsent(Request $request): JsonResponse
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $testEmail = trim($request->test_email);

        // ADDITIONAL VALIDATION: Ensure test email is valid
        if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL) ||
            strpos($testEmail, '@') === false ||
            preg_match('/\s/', $testEmail)) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz test e-posta adresi: ' . $testEmail,
            ], 400);
        }

        // Create a mock user object with the validated test email
        $mockUser = (object) [
            'email' => $testEmail,
            'name' => 'Test Kullanıcısı',
        ];

        try {
            $log = $this->automaticEmailService->sendKvkkConsentTest($mockUser);

            if (!$log || $log->status === 'failed') {
                $errorMessage = $log?->error_message ?? null;

                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                        ? 'Test e-postası gönderilemedi: ' . $errorMessage
                        : 'Test e-postası gönderilemedi. Lütfen e-posta ayarlarınızı kontrol edin.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Test e-postası başarıyla gönderildi.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test e-postası gönderilirken hata oluştu: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function getClinicName(): string
    {
        $value = Setting::where('key', 'clinic_name')->first()?->value;

        if (is_array($value)) {
            $first = reset($value);
            if (is_string($first) && $first !== '') {
                return $first;
            }
        }

        if (is_string($value) && $value !== '') {
            return $value;
        }

        $appName = config('app.name');

        return is_string($appName) && $appName !== '' ? $appName : 'Klinik';
    }
}
