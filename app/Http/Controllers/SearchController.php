<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\Stock\StockSupplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /**
     * Search patients for quick actions
     */
    public function patients(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 10);

        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $patients = Patient::where(function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
              ->orWhere('last_name', 'like', "%{$query}%")
              ->orWhere('phone_primary', 'like', "%{$query}%")
              ->orWhere('national_id', 'like', "%{$query}%")
              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"]);
        })
        ->select(['id', 'first_name', 'last_name', 'phone_primary', 'national_id'])
        ->limit($limit)
        ->get()
        ->map(function ($patient) {
            return [
                'id' => $patient->id,
                'text' => $patient->first_name . ' ' . $patient->last_name,
                'phone' => $patient->phone_primary,
                'national_id' => $patient->national_id,
            ];
        });

        return response()->json(['data' => $patients]);
    }

    /**
     * Search appointments for quick actions
     */
    public function appointments(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 10);

        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $appointments = Appointment::with(['patient', 'dentist'])
            ->whereHas('patient', function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"]);
            })
            ->orWhereHas('dentist', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->select(['id', 'patient_id', 'dentist_id', 'start_at', 'status'])
            ->orderBy('start_at')
            ->limit($limit)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'text' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name .
                             ' - ' . $appointment->start_at->format('d.m.Y H:i'),
                    'patient_name' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                    'dentist_name' => $appointment->dentist->name,
                    'date' => $appointment->start_at->format('d.m.Y H:i'),
                    'status' => $appointment->status->label(),
                ];
            });

        return response()->json(['data' => $appointments]);
    }

    /**
     * Search treatment plans for quick actions
     */
    public function treatmentPlans(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 10);

        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $plans = TreatmentPlan::with(['patient', 'dentist'])
            ->whereHas('patient', function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"]);
            })
            ->where('status', 'active')
            ->select(['id', 'patient_id', 'dentist_id', 'created_at', 'total_estimated_cost'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'text' => $plan->patient->first_name . ' ' . $plan->patient->last_name .
                             ' - Plan #' . $plan->id,
                    'patient_name' => $plan->patient->first_name . ' ' . $plan->patient->last_name,
                    'dentist_name' => $plan->dentist->name,
                    'created_at' => $plan->created_at->format('d.m.Y'),
                    'cost' => number_format($plan->total_estimated_cost, 2, ',', '.'),
                ];
            });

        return response()->json(['data' => $plans]);
    }

    /**
     * Search suppliers for quick actions
     */
    public function suppliers(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 10);

        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $suppliers = StockSupplier::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('phone', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%");
        })
        ->select(['id', 'name', 'phone', 'email'])
        ->limit($limit)
        ->get()
        ->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'text' => $supplier->name,
                'phone' => $supplier->phone,
                'email' => $supplier->email,
            ];
        });

        return response()->json(['data' => $suppliers]);
    }
}
