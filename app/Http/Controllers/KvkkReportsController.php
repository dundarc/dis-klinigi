<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\ConsentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KvkkReportsController extends Controller
{
    /**
     * Onamı olmayan hastaları listele.
     */
    public function missingConsents(Request $request): View
    {
        $this->authorize('viewReports', Patient::class);

        $query = $request->get('q', '');

        // Base query for patients with KVKK issues
        $baseQuery = Patient::query();

        if (strlen($query) >= 2) {
            $baseQuery->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('national_id', 'like', "%{$query}%");
            });
        }

        // Get patients who don't have any active consents OR have canceled consents
        // Check for both new ('active') and old ('accepted') status values for backward compatibility
        $patients = $baseQuery->where(function ($query) {
            $query->whereDoesntHave('consents', function ($q) {
                $q->whereIn('status', ['active', 'accepted']);
            })->orWhereHas('consents', function ($q) {
                $q->whereIn('status', ['canceled', 'withdrawn']);
            });
        })->with(['consents' => fn($q) => $q->latest()])->paginate(20);

        // Add status information
        $patients->getCollection()->transform(function ($patient) {
            $latestConsent = $patient->consents->first();
            $status = 'no_consent';

            if ($latestConsent) {
                if (in_array($latestConsent->status, ['canceled', 'withdrawn'])) {
                    $status = 'canceled';
                } elseif (in_array($latestConsent->status, ['active', 'accepted'])) {
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
                $query->whereIn('status', ['active', 'accepted']);
            })->count(),
            'canceled_consents' => Patient::whereHas('consents', function ($query) {
                $query->whereIn('status', ['canceled', 'withdrawn']);
            })->count(),
            'total_issues' => $patients->total(),
        ];

        return view('kvkk.reports.missing-consents', compact('patients', 'stats', 'query'));
    }
}
