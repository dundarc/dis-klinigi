<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. Ekle
class PatientController extends Controller
{
        use AuthorizesRequests; // 2. Ekle
    public function index()
    {
        $this->authorize('viewAny', Patient::class);

        $user = auth()->user();
        
        if ($user->role === UserRole::DENTIST) {
            // Hekim, sadece kendisine randevusu olan hastaları görür
            $patients = Patient::whereHas('appointments', function ($query) use ($user) {
                $query->where('dentist_id', $user->id);
            })->latest()->paginate(20);
        } else {
            // Admin ve Resepsiyonist tüm hastaları görür
            $patients = Patient::latest()->paginate(20);
        }

        return view('patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $this->authorize('view', $patient);
        $patient->load(['treatments.dentist', 'invoices', 'prescriptions.dentist', 'files.uploader']);
        return view('patients.show', compact('patient'));
    }

    // Diğer metodları (create, store vb.) sonraki adımlarda ekleyeceğiz.
}