<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\ConsentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientKvkkController extends Controller
{
    /**
     * Display missing consents report
     */
    public function missingConsents(Request $request): View
    {
        $this->authorize('viewAny', Patient::class);

        $query = $request->get('q', '');

        // Base query for patients with KVKK issues
        $baseQuery = Patient::query();

        if (strlen($query) >= 2) {
            $baseQuery->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('national_id', 'like', "%{$query}%")
                  ->orWhere('phone_primary', 'like', "%{$query}%");
            });
        }

        // Get patients who don't have any accepted consents OR have withdrawn consents OR have expired consents
        $patients = $baseQuery->where(function ($query) {
            $query->whereDoesntHave('consents', function ($q) {
                $q->where('status', 'accepted');
            })->orWhereHas('consents', function ($q) {
                $q->where('status', 'withdrawn');
            })->orWhereHas('consents', function ($q) {
                $q->where('status', 'accepted')
                  ->where('accepted_at', '<', now()->subYear());
            });
        })->with(['consents' => fn($q) => $q->latest()])->paginate(20);

        // Add status information
        $patients->getCollection()->transform(function ($patient) {
            $latestConsent = $patient->consents->first();
            $oneYearAgo = now()->subYear();
            $status = 'no_consent';

            if ($latestConsent) {
                if ($latestConsent->status === 'withdrawn') {
                    $status = 'withdrawn';
                } elseif ($latestConsent->accepted_at && $latestConsent->accepted_at->lt($oneYearAgo)) {
                    $status = 'expired';
                } elseif ($latestConsent->status === 'accepted') {
                    $status = 'has_consent';
                }
            }

            $patient->kvkk_status = $status;
            $patient->latest_consent_at = $latestConsent?->accepted_at;
            $patient->consent_status = $latestConsent?->status;

            return $patient;
        });

        $stats = [
            'total_patients' => Patient::count(),
            'missing_consents' => Patient::whereDoesntHave('consents', function ($query) {
                $query->where('status', 'accepted');
            })->count(),
            'withdrawn_consents' => Patient::whereHas('consents', function ($query) {
                $query->where('status', 'withdrawn');
            })->count(),
            'expired_consents' => Patient::whereHas('consents', function ($query) {
                $query->where('status', 'accepted')
                      ->where('accepted_at', '<', now()->subYear());
            })->count(),
            'total_issues' => $patients->total(),
        ];

        return view('patients.kvkk.reports.missing', compact('patients', 'stats', 'query'));
    }

    /**
     * Show consent form for patient
     */
    public function showConsentForm(Patient $patient): View
    {
        $this->authorize('view', $patient);

        // Check if patient already has valid consent
        if ($patient->hasKvkkConsent()) {
            // Redirect to show page if consent is valid
            return redirect()->route('patients.kvkk.show', $patient)->with('info', 'Bu hastanın geçerli KVKK onamı zaten bulunmaktadır.');
        }

        return view('patients.kvkk.consent', compact('patient'));
    }

    /**
     * Store patient consent
     */
    public function storeConsent(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);

        $request->validate([
            'consent_accepted' => 'required|accepted',
            'signature' => 'nullable|string',
        ]);

        try {
            $signaturePath = null;

            // Save signature if provided
            if ($request->signature) {
                $signaturePath = $this->saveSignature($request->signature, $patient->id);
            }

            // Create KVKK consent snapshot
            $snapshot = [
                'version' => '1.0',
                'patient_info' => [
                    'id' => $patient->id,
                    'first_name' => $patient->first_name,
                    'last_name' => $patient->last_name,
                    'national_id' => $patient->national_id,
                    'phone_primary' => $patient->phone_primary,
                    'email' => $patient->email,
                ],
                'consent_text' => 'KVKK Aydınlatma Metni - Versiyon 1.0',
                'accepted_at' => now()->toISOString(),
                'signature_path' => $signaturePath,
            ];

            // Create consent record
            $consent = ConsentService::accept($patient, $snapshot, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Update patient's consent timestamp
            $patient->update(['consent_kvkk_at' => now()]);

            return redirect()->route('patients.kvkk.show', $patient)
                           ->with('success', 'KVKK onamı başarıyla kaydedildi.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Onam kaydedilirken bir hata oluştu: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Save signature image to storage
     */
    private function saveSignature(string $signatureData, int $patientId): string
    {
        // Create directory if it doesn't exist
        $directory = storage_path('app/public/consents');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Generate filename
        $filename = 'signature_' . $patientId . '_' . time() . '.png';
        $filepath = $directory . '/' . $filename;

        // Remove data URL prefix and decode
        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);
        $signatureBlob = base64_decode($signatureData);

        // Save file
        file_put_contents($filepath, $signatureBlob);

        return 'consents/' . $filename;
    }

    /**
     * Show patient KVKK details after consent
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

        return view('patients.kvkk.show', compact('patient'));
    }
}