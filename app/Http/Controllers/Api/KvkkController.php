<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\ConsentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KvkkController extends Controller
{
    /**
     * Get consent status for a patient.
     *
     * Returns JSON: {status: accepted|withdrawn|null, latest_at: ISO}
     */
    /**
     * Get consent status for a patient.
     *
     * Returns JSON: {status: accepted|withdrawn|null, latest_at: ISO}
     */
    public function consentStatus(Patient $patient): JsonResponse
    {
        try {
            $this->authorize('view', $patient);

            return response()->json([
                'status' => $patient->hasKvkkConsent() ? 'accepted' : null,
                'latest_at' => $patient->latestConsent()?->accepted_at?->toISOString(),
            ]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Patient not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
