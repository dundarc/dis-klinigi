<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\PatientDataEraserService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. Ekle
class PatientErasureController extends Controller
{
        use AuthorizesRequests; // 2. Ekle
    public function __construct(protected PatientDataEraserService $eraserService)
    {
    }


    public function erase(Request $request, Patient $patient)
    {
        // Bu işlemi sadece admin yapabilir. Gate::before bunu zaten sağlıyor.
        // Ama yine de bir policy kontrolü eklemek best practice'dir.
        $this->authorize('delete', $patient);

        $this->eraserService->erase($patient);

        return response()->json(['message' => "{$patient->first_name} {$patient->last_name} isimli hasta ve tüm verileri kalıcı olarak silindi."], 200);
    }
}