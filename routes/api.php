<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\EncounterController;
use App\Http\Controllers\Api\V1\AppointmentReferralController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\PatientTreatmentController;
use App\Http\Controllers\Api\V1\PatientFileController;
use App\Http\Controllers\Api\V1\PrescriptionController;
use App\Http\Controllers\Api\V1\Admin\PatientAssignmentController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\Accounting\InvoiceManagementController;
use App\Http\Controllers\Api\V1\Accounting\FinancialReportController;
use App\Http\Controllers\Api\V1\Admin\PatientErasureController;
use App\Http\Controllers\Api\V1\TreatmentPlanController as ApiTreatmentPlanController; // API Controller iÃ§in alias
use App\Http\Controllers\PatientController; // EKLENDÄ°
use App\Http\Controllers\PatientController as ApiPatientController; // API Controller iÃ§in alias

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Herkese aÃ§Ä±k rotalar (GiriÅŸ)
Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
});

// Kimlik doÄŸrulamasÄ± gerektiren rotalar
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth RotalarÄ±
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Randevu RotalarÄ±
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('prescriptions', PrescriptionController::class)->except(['index']);
    Route::post('appointments/{appointment}/check-in', [AppointmentController::class, 'checkIn']);
    Route::post('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
    Route::post('appointments/{appointment}/call', [AppointmentController::class, 'call']);

    // Vaka (Encounter) RotalarÄ±
    Route::post('encounters/{encounter}/status', [EncounterController::class, 'updateStatus']);
    Route::post('encounters/{encounter}/assign-doctor', [EncounterController::class, 'assignDoctor']);
    Route::post('encounters/{encounter}/assign-and-process', [EncounterController::class, 'assignAndProcess']);

    // Randevu Sevk RotalarÄ±
    Route::post('appointments/{appointment}/refer', [AppointmentReferralController::class, 'store']);
    Route::post('appointments/{appointment}/accept-referral', [AppointmentReferralController::class, 'update']);
    
    // Fatura, Tedavi ve Dosya RotalarÄ±
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('patient-treatments', [PatientTreatmentController::class, 'store']);
    Route::post('patients/{patient}/files', [PatientFileController::class, 'store']);
    Route::patch('files/{file}', [PatientFileController::class, 'update']);
    Route::delete('files/{file}', [PatientFileController::class, 'destroy']);

    // Treatment Plan Item Status Updates
    Route::post('treatment-plan-items/{item}/complete', [\App\Http\Controllers\Api\V1\TreatmentPlanItemController::class, 'complete']);
    Route::post('treatment-plan-items/{item}/cancel', [\App\Http\Controllers\Api\V1\TreatmentPlanItemController::class, 'cancel']);
    Route::post('treatment-plan-items/{item}/start', [\App\Http\Controllers\Api\V1\TreatmentPlanItemController::class, 'start']);

    // Encounter Treatment Plan Items
    Route::get('encounters/{encounter}/treatment-plan-items', [\App\Http\Controllers\Api\V1\EncounterController::class, 'getTreatmentPlanItems']);
    Route::post('encounters/{encounter}/auto-save-treatments', [\App\Http\Controllers\Api\V1\EncounterController::class, 'autoSaveTreatments']);
    Route::post('encounters/{encounter}/auto-save-prescription', [PrescriptionController::class, 'autoSavePrescription']);

    
    // Admin'e Ã¶zel rotalar
    Route::prefix('admin')->middleware('can:accessAdminFeatures')->group(function () {
        Route::post('assign-patient', [PatientAssignmentController::class, 'store']);
        Route::delete('patients/{patient}/erase', [PatientErasureController::class, 'erase']);
    });
    
    // TÃ¼m kullanÄ±cÄ±lar iÃ§in bildirim rotalarÄ±
    Route::get('notifications', [NotificationController::class, 'index']);

    // Treatment Plan API routes
    Route::get('treatment-plans/{treatmentPlan}', [ApiTreatmentPlanController::class, 'show']);
    Route::get('treatment-plans/{treatmentPlan}/items', [\App\Http\Controllers\Api\V1\TreatmentPlanItemController::class, 'index']); // Existing items API
    

        Route::get('/patients/{patient}/uninvoiced-treatments', [PatientController::class, 'getUninvoicedTreatments']);
        Route::get('/patients/{patient}/uninvoiced-treatments', [ApiPatientController::class, 'getUninvoicedTreatments']);


    // Muhasebe ve Admin'e Ã¶zel rotalar
    Route::prefix('accounting')->middleware('can:accessAccountingFeatures')->group(function () {
        Route::patch('invoices/{invoice}/status', [InvoiceManagementController::class, 'updateStatus']);
        Route::patch('invoices/{invoice}/insurance', [InvoiceManagementController::class, 'updateInsurance']);
        Route::post('invoices/{invoice}/send-email', [InvoiceManagementController::class, 'sendEmail']);
        Route::get('reports/financial', [FinancialReportController::class, 'summary']);
    });

    // API v1 - Accounting Routes (Commented out - controllers don't exist)
    // Route::prefix('v1/accounting')->middleware('can:accessAccountingFeatures')->group(function () {
    //     Route::get('/invoices', [\App\Http\Controllers\Api\V1\Accounting\InvoiceController::class, 'index']);
    //     Route::post('/invoices', [\App\Http\Controllers\Api\V1\Accounting\InvoiceController::class, 'store']);
    //     Route::get('/invoices/{invoice}', [\App\Http\Controllers\Api\V1\Accounting\InvoiceController::class, 'show']);
    //     Route::put('/invoices/{invoice}', [\App\Http\Controllers\Api\V1\Accounting\InvoiceController::class, 'update']);
    //     Route::delete('/invoices/{invoice}', [\App\Http\Controllers\Api\V1\Accounting\InvoiceController::class, 'destroy']);
    //
    //     Route::get('/reports/revenue', [\App\Http\Controllers\Api\V1\Accounting\ReportController::class, 'revenue']);
    //     Route::get('/reports/overdue', [\App\Http\Controllers\Api\V1\Accounting\ReportController::class, 'overdue']);
    //     Route::get('/reports/dentist-performance', [\App\Http\Controllers\Api\V1\Accounting\ReportController::class, 'dentistPerformance']);
    //     Route::get('/reports/payment-methods', [\App\Http\Controllers\Api\V1\Accounting\ReportController::class, 'paymentMethods']);
    // });
});

