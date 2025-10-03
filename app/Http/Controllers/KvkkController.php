<?php

namespace App\Http\Controllers;

use App\Models\KvkkAuditLog;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\EmailAutomationSetting;
use App\Services\ConsentService;
use App\Services\ExportBuilder;
use App\Services\KvkkDeletionService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KvkkController extends Controller
{
    /**
     * KVKK süreç anlatımı + hasta arama + onam durumu kısa özet.
     */
    public function index(Request $request): View
    {
        $this->authorize('accessKvkkFeatures');

        $query = $request->get('q', '');
        $patients = collect();

        if (strlen($query) >= 2) {
            $patients = Patient::where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('national_id', 'like', "%{$query}%");
            })->with('consents')->take(20)->get();
        }

        // Add consent status to each patient
        $patients->transform(function ($patient) {
            $patient->consent_status = $patient->hasKvkkConsent() ? 'accepted' : null;
            $patient->consent_latest_at = $patient->latestConsent()?->accepted_at?->toISOString();
            return $patient;
        });

        return view('kvkk.index', compact('patients', 'query'));
    }

    /**
     * Live search for patients (AJAX endpoint) - returns all patients if no query
     */
    public function search(Request $request)
    {
        $this->authorize('accessKvkkFeatures');

        $query = $request->get('q', '');
        $patients = collect();

        if (strlen($query) >= 2) {
            // Search with query
            $patients = Patient::where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('national_id', 'like', "%{$query}%")
                  ->orWhere('phone_primary', 'like', "%{$query}%");
            })->with('consents')->get();
        } elseif (strlen($query) === 0) {
            // Return all patients when no query (for pagination)
            $patients = Patient::with('consents')->get();
        }

        // Add consent status to each patient
        $patients->transform(function ($patient) {
            return [
                'id' => $patient->id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'national_id' => $patient->national_id,
                'phone_primary' => $patient->phone_primary,
                'consent_status' => $patient->hasKvkkConsent() ? 'accepted' : null,
                'latest_consent_at' => $patient->latestConsent()?->accepted_at?->toISOString(),
            ];
        });

        return response()->json($patients);
    }

    /**
     * Hasta KVKK ekranı.
     */
    public function show(Patient $patient): View
    {
        $this->authorize('view', $patient);

        $patient->load([
            'consents' => fn($q) => $q->latest(),
            'kvkkAuditLogs' => fn($q) => $q->with('user')->latest(),
            'appointments' => fn($q) => $q->latest()->take(5),
            'invoices' => fn($q) => $q->with('payments')->latest()->take(5),
            'treatmentPlans' => fn($q) => $q->latest()->take(5),
            'files' => fn($q) => $q->latest()->take(5),
        ]);

        $latestConsent = $patient->latestConsent();

        $consentStatus = [
            'status' => $patient->hasKvkkConsent() ? 'active' : null,
            'pending' => $latestConsent && $latestConsent->status === \App\Enums\ConsentStatus::PENDING,
            'method' => $latestConsent?->consent_method,
            'latest_at' => $latestConsent?->accepted_at?->toISOString(),
            'email_verified' => $latestConsent?->email_verified_at !== null,
        ];

        return view('kvkk.show', compact('patient', 'consentStatus'));
    }

    /**
     * Show export form (GET) or perform export (POST)
     */
    public function export(Request $request, Patient $patient)
    {
        $this->authorize('export', $patient);

        if ($request->isMethod('get')) {
            $format = $request->get('format', 'zip');

            if ($format === 'pdf') {
                // PDF export - stream directly
                return $this->exportPdf($patient);
            }

            // Check if it's AJAX request (from modal)
            if ($request->ajax() || $request->wantsJson()) {
                // ZIP export from modal - return JSON
                $masking = $request->boolean('masking', false);
                $result = $this->exportZip($patient, $masking, [
                    'appointments' => true,
                    'treatment_plans' => true,
                    'files' => true,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'KVKK export paketi oluşturuldu.',
                    'download_url' => $result,
                    'filename' => basename(parse_url($result, PHP_URL_PATH)),
                ]);
            }

            // Show export form
            return view('kvkk.export', compact('patient'));
        }

        // Handle POST export
        $exportAppointments = $request->boolean('export_appointments', false);
        $exportTreatmentPlans = $request->boolean('export_treatment_plans', false);
        $exportFiles = $request->boolean('export_files', false);
        $consentConfirmation = $request->boolean('consent_confirmation', false);

        if (!$consentConfirmation) {
            return back()->with('error', 'Verilerin onam formuna uygun dışarı aktarma işlemi yapıldığını onaylamanız gerekir.');
        }

        if (!$exportAppointments && !$exportTreatmentPlans && !$exportFiles) {
            return back()->with('error', 'En az bir export seçeneği seçmelisiniz.');
        }

        // Perform ZIP export with selected options
        $downloadUrl = $this->exportZip($patient, true, [
            'appointments' => $exportAppointments,
            'treatment_plans' => $exportTreatmentPlans,
            'files' => $exportFiles,
        ]);

        // Log the export action
        KvkkAuditLog::create([
            'patient_id' => $patient->id,
            'user_id' => auth()->id(),
            'action' => \App\Enums\KvkkAuditAction::EXPORT,
            'ip_address' => request()->ip(),
            'meta' => [
                'exported_data' => [
                    'appointments' => $exportAppointments,
                    'treatment_plans' => $exportTreatmentPlans,
                    'files' => $exportFiles,
                ],
                'filename' => basename(parse_url($downloadUrl, PHP_URL_PATH)),
            ],
        ]);

        // Since we're on a web page, redirect back with success message and download URL
        return redirect()->back()->with([
            'success' => 'KVKK export paketi oluşturuldu.',
            'download_url' => $downloadUrl
        ]);
    }

    /**
     * Export patient consent as PDF
     */
    private function exportPdf(Patient $patient)
    {
        $consent = $patient->consents()->latest('accepted_at')->first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kvkk.export-pdf', compact('patient', 'consent'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('kvkk_consent_' . $patient->id . '.pdf');
    }

    /**
     * Export patient data as ZIP with selected content
     */
    private function exportZip(Patient $patient, bool $masking, array $options = [])
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "patient_{$patient->id}_{$timestamp}.zip";
        $zipPath = storage_path("app/exports/{$filename}");

        // Ensure exports directory exists
        $exportsDir = storage_path('app/exports');
        if (!file_exists($exportsDir)) {
            mkdir($exportsDir, 0755, true);
        }

        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            // Load patient with selected relationships
            $loadRelations = ['consents'];
            if ($options['appointments'] ?? false) $loadRelations[] = 'appointments';
            if ($options['treatment_plans'] ?? false) $loadRelations[] = 'treatmentPlans.items';
            if ($options['files'] ?? false) $loadRelations[] = 'files';

            $patient->load($loadRelations);

            // Build export data
            $data = ExportBuilder::build($patient, ['masking' => $masking]);

            // Add JSON data
            $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $zip->addFromString('patient_data.json', $jsonContent);

            // Always add consent PDF
            $consent = $patient->consents()->latest('accepted_at')->first();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kvkk.export-pdf', compact('patient', 'consent'));
            $pdf->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();
            $zip->addFromString('kvkk_consent.pdf', $pdfContent);

            // Add appointments PDF if selected
            if ($options['appointments'] ?? false) {
                $appointmentsPdf = $this->generateAppointmentsPdf($patient, $masking);
                $zip->addFromString('appointments.pdf', $appointmentsPdf);
            }

            // Add treatment plans PDF if selected
            if ($options['treatment_plans'] ?? false) {
                $treatmentPlansPdf = $this->generateTreatmentPlansPdf($patient, $masking);
                $zip->addFromString('treatment_plans.pdf', $treatmentPlansPdf);
            }

            // Add files if selected
            if ($options['files'] ?? false) {
                foreach ($patient->files as $file) {
                    $filePath = storage_path('app/' . $file->path);
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, 'files/' . $file->filename);
                    }
                }
            }

            $zip->close();
        }

        // Return download URL
        return route('kvkk.download-export', $filename);
    }

    /**
     * Download exported ZIP file
     */
    public function downloadExport(Request $request, $filename)
    {
        $filePath = storage_path("app/exports/{$filename}");

        if (!file_exists($filePath)) {
            abort(404, 'Export dosyası bulunamadı.');
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    /**
     * Soft delete işlemi.
     */
    public function softDelete(Patient $patient): RedirectResponse
    {
        $this->authorize('softDelete', $patient);

        try {
            KvkkDeletionService::softDelete($patient, auth()->user());

            return redirect()->route('kvkk.index')
                ->with('success', 'Hasta verileri KVKK uyumluluğu için işaretlenmiştir.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'İşlem sırasında bir hata oluştu.');
        }
    }

    /**
     * Hard delete onay sayfası.
     */
    public function hardDeleteConfirm(Patient $patient): View
    {
        $this->authorize('hardDelete', $patient);

        $patient->load([
            'appointments' => fn($q) => $q->latest()->take(5),
            'invoices' => fn($q) => $q->with('payments')->latest()->take(5),
            'treatmentPlans' => fn($q) => $q->latest()->take(5),
            'files' => fn($q) => $q->latest()->take(5),
        ]);

        return view('kvkk.hard-delete-confirm', compact('patient'));
    }

    /**
     * Hard delete işlemi (sadece admin).
     */
    public function hardDelete($patientId): RedirectResponse
    {
        try {
            $patient = Patient::withTrashed()->findOrFail($patientId);
            $this->authorize('hardDelete', $patient);

            \Log::info('Hard delete initiated', [
                'patient_id' => $patientId,
                'patient_found' => $patient ? 'yes' : 'no',
                'user_id' => auth()->id()
            ]);

            $result = KvkkDeletionService::hardDelete($patient, auth()->user());

            \Log::info('Hard delete completed', [
                'patient_id' => $patientId,
                'result' => $result
            ]);

            return redirect()->route('kvkk.trash.index')
                ->with('success', 'Hasta verileri kalıcı olarak silinmiştir.');
        } catch (\Exception $e) {
            \Log::error('Hard delete failed', [
                'patient_id' => $patientId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'İşlem sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Restore index sayfası - tüm soft delete edilmiş hastaları göster.
     */
    public function restoreIndex(): View
    {
        $this->authorize('restore', Patient::first() ?? new Patient());

        $deletedPatients = Patient::onlyTrashed()
            ->with(['consents', 'kvkkAuditLogs' => fn($q) => $q->latest()])
            ->paginate(20);

        return view('kvkk.restore', compact('deletedPatients'));
    }

    /**
     * Restore işlemi (sadece admin) - tüm soft delete edilmiş hastalar için.
     */
    public function restore($patientId): RedirectResponse
    {
        $patient = Patient::withTrashed()->findOrFail($patientId);
        $this->authorize('restore', $patient);

        try {
            // Check if patient was deleted via KVKK process
            if ($patient->deleted_via === 'kvkk') {
                // Use KVKK service for proper restoration with related records
                KvkkDeletionService::restore($patient, auth()->user());
            } else {
                // Simple restore for patients deleted through other means
                $patient->restore();
                $patient->update(['deleted_via' => null]);
            }

            return redirect()->route('kvkk.trash.index')
                ->with('success', 'Hasta verileri geri yüklenmiştir.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'İşlem sırasında bir hata oluştu.');
        }
    }

    /**
     * Bulk restore işlemi (sadece admin).
     */
    public function bulkRestore(Request $request): RedirectResponse
    {
        $patientIds = $request->input('patient_ids', []);
        if (is_string($patientIds)) {
            $patientIds = explode(',', $patientIds);
        }

        if (empty($patientIds)) {
            return redirect()->back()->with('error', 'Hiç hasta seçilmedi.');
        }

        $restoredCount = 0;
        $errors = [];

        foreach ($patientIds as $patientId) {
            try {
                $patient = Patient::withTrashed()->find($patientId);
                if ($patient) {
                    $this->authorize('restore', $patient);
                    KvkkDeletionService::restore($patient, auth()->user());
                    $restoredCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Hasta ID {$patientId}: " . $e->getMessage();
            }
        }

        $message = "{$restoredCount} hasta başarıyla geri yüklendi.";
        if (!empty($errors)) {
            $message .= " Hatalar: " . implode(', ', $errors);
        }

        return redirect()->route('kvkk.trash.index')
            ->with('success', $message);
    }

    /**
     * Bulk hard delete işlemi (sadece admin).
     */
    public function bulkHardDelete(Request $request): RedirectResponse
    {
        $patientIds = $request->input('patient_ids', []);
        if (is_string($patientIds)) {
            $patientIds = explode(',', $patientIds);
        }

        if (empty($patientIds)) {
            return redirect()->back()->with('error', 'Hiç hasta seçilmedi.');
        }

        $deletedCount = 0;
        $errors = [];

        foreach ($patientIds as $patientId) {
            try {
                $patient = Patient::withTrashed()->find($patientId);
                if ($patient) {
                    $this->authorize('hardDelete', $patient);
                    KvkkDeletionService::hardDelete($patient, auth()->user());
                    $deletedCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Hasta ID {$patientId}: " . $e->getMessage();
            }
        }

        $message = "{$deletedCount} hasta kalıcı olarak silindi.";
        if (!empty($errors)) {
            $message .= " Hatalar: " . implode(', ', $errors);
        }

        return redirect()->route('kvkk.trash.index')
            ->with('success', $message);
    }

    /**
     * Generate appointments PDF
     */
    private function generateAppointmentsPdf(Patient $patient, bool $masking): string
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kvkk.export-appointments', compact('patient', 'masking'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->output();
    }

    /**
     * Generate treatment plans PDF
     */
    private function generateTreatmentPlansPdf(Patient $patient, bool $masking): string
    {
        $treatmentPlans = $patient->treatmentPlans()
            ->with([
                'items.treatment',
                'items.appointment',
                'dentist'
            ])
            ->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kvkk.export-treatment-plans', compact('patient', 'treatmentPlans', 'masking'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->output();
    }

    /**
     * Show cancel consent form
     */
    public function cancelConsent(Patient $patient): View
    {
        $this->authorize('manageConsents', $patient);

        // Check if patient has any active consent
        if (!$patient->hasKvkkConsent()) {
            abort(404, 'Aktif onay bulunamadı.');
        }

        return view('kvkk.cancel-consent', compact('patient'));
    }

    /**
     * Download cancellation PDF
     */
    public function downloadCancellationPdf(Patient $patient)
    {
        $this->authorize('manageConsents', $patient);

        $consent = $patient->latestConsent();
        if (!$consent || $consent->status !== \App\Enums\ConsentStatus::ACTIVE) {
            abort(404, 'Aktif onay bulunamadı.');
        }

        // Generate PDF if not already generated
        if (!$consent->cancellation_pdf_generated_at) {
            $consent = \App\Services\Kvkk\ConsentService::initiateCancellation($consent);
        }

        // Mark as downloaded
        $consent = \App\Services\Kvkk\ConsentService::markPdfDownloaded($consent);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kvkk.cancel-consent-pdf', compact('patient', 'consent'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('kvkk_iptal_talebi_' . $patient->id . '.pdf');
    }

    /**
     * Show create consent form
     */
    public function createConsent(Patient $patient): View
    {
        $this->authorize('manageConsents', $patient);

        // Check if patient already has active consent
        if ($patient->hasKvkkConsent()) {
            return redirect()->route('kvkk.show', $patient)->with('info', 'Bu hasta zaten aktif KVKK onamına sahip.');
        }

        return view('kvkk.create-consent', compact('patient'));
    }

    /**
     * Store new consent
     */
    public function storeConsent(Request $request, Patient $patient): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('manageConsents', $patient);

        $request->validate([
            'consent_method' => 'required|in:wet_signature,email_verification',
            'version' => 'required|string',
            'snapshot' => 'required|array',
        ]);

        // Check if patient already has active consent
        if ($patient->hasKvkkConsent()) {
            return redirect()->route('kvkk.show', $patient)->with('error', 'Bu hasta zaten aktif KVKK onamına sahip.');
        }

        try {
            $consentMethod = $request->consent_method;
            $verificationToken = null;
            $status = \App\Enums\ConsentStatus::ACTIVE;

            if ($consentMethod === 'email_verification') {
                $verificationToken = \Illuminate\Support\Str::random(64);
                $status = \App\Enums\ConsentStatus::PENDING; // Pending until email verified
            }

            $data = [
                'version' => $request->version,
                'status' => $status,
                'consent_method' => $consentMethod,
                'verification_token' => $verificationToken,
                'snapshot' => $request->snapshot,
                'meta' => [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ];

            $consent = \App\Services\Kvkk\ConsentService::register($patient, $data);

            // Otomatik e-posta bildirimi gönder (eğer aktifse)
            $automationSettings = EmailAutomationSetting::getSettings();
            if ($automationSettings && $automationSettings->kvkk_consent_to_admin) {
                try {
                    EmailService::sendTemplate('kvkk_consent_notification', [
                        'to' => auth()->user()->email, // Send to current admin user
                        'data' => [
                            'patient_name' => $patient->first_name . ' ' . $patient->last_name,
                            'consent_date' => $consent->accepted_at->format('d.m.Y H:i'),
                            'clinic_name' => Setting::where('key', 'clinic_name')->first()?->value ?? 'Klinik'
                        ]
                    ]);
                } catch (\Exception $e) {
                    // E-posta gönderimi başarısız olsa bile consent işlemi devam eder
                    \Log::error('KVKK consent email failed: ' . $e->getMessage());
                }
            }

            if ($consentMethod === 'email_verification' && $patient->email) {
                // Send email verification
                \App\Services\Kvkk\ConsentService::sendEmailVerification($consent);
                return redirect()->route('kvkk.consent-success', $patient)->with('success', 'KVKK onamı oluşturuldu ve doğrulama e-postası gönderildi.');
            }

            return redirect()->route('kvkk.consent-success', $patient)->with('success', 'KVKK onamı başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Onam oluşturulurken hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show consent creation success page
     */
    public function consentSuccess(Patient $patient): View
    {
        $this->authorize('view', $patient);

        $consent = $patient->latestConsent();
        if (!$consent) {
            return redirect()->route('kvkk.show', $patient)->with('error', 'Onam bilgisi bulunamadı.');
        }

        return view('kvkk.consent-success', compact('patient', 'consent'));
    }

    /**
     * Process consent cancellation
     */
    public function processCancelConsent(Patient $patient): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('manageConsents', $patient);

        // Check if patient has any active consent
        if (!$patient->hasKvkkConsent()) {
            return redirect()->back()->with('error', 'Aktif onay bulunamadı.');
        }

        try {
            // Cancel all active consents for this patient
            $canceled = \App\Services\Kvkk\ConsentService::cancelActive($patient, auth()->user());
            if ($canceled) {
                return redirect()->route('kvkk.show', $patient)->with('success', 'KVKK onamı başarıyla iptal edildi.');
            } else {
                return redirect()->back()->with('error', 'İptal edilecek aktif onay bulunamadı.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'İptal işlemi sırasında hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Download consent PDF
     */
    public function downloadConsentPdf(Patient $patient): \Illuminate\Http\Response
    {
        $this->authorize('view', $patient);

        $consent = $patient->latestConsent();
        if (!$consent) {
            abort(404, 'Consent not found');
        }

        $settings = Setting::all()->pluck('value', 'key')->all();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kvkk.consent-pdf', compact('consent', 'settings'));
        $filename = 'kvkk-consent-' . $patient->id . '-' . $consent->id . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show consent verification page
     */
    public function showVerifyConsent(string $token): \Illuminate\View\View
    {
        try {
            $consent = \App\Models\Consent::where('verification_token', $token)
                ->where('consent_method', 'email_verification')
                ->where('status', \App\Enums\ConsentStatus::PENDING)
                ->whereNull('email_verified_at')
                ->with('patient')
                ->first();

            if (!$consent) {
                abort(404, 'Geçersiz doğrulama bağlantısı');
            }

            return view('kvkk.verify-consent', compact('consent', 'token'));
        } catch (\Exception $e) {
            abort(404, 'Doğrulama bağlantısı geçersiz');
        }
    }

    /**
     * Process consent verification
     */
    public function processVerifyConsent(Request $request, string $token): \Illuminate\Http\RedirectResponse
    {
        try {
            $consent = \App\Services\Kvkk\ConsentService::verifyEmailConsent($token);

            return redirect()->route('welcome')
                ->with('success', 'KVKK onamınız başarıyla doğrulandı. Teşekkür ederiz.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Onam doğrulama işlemi başarısız oldu. Lütfen tekrar deneyin.');
        }
    }
}
