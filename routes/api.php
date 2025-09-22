<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\EncounterController;
use App\Http\Controllers\Api\V1\AppointmentReferralController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\PatientTreatmentController;
use App\Http\Controllers\Api\V1\PatientFileController;
use App\Http\Controllers\Api\V1\Admin\PatientAssignmentController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\Accounting\InvoiceManagementController;
use App\Http\Controllers\Api\V1\Accounting\FinancialReportController;
use App\Http\Controllers\Api\V1\Admin\PatientErasureController;

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

// Herkese açık rotalar (Giriş)
Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
});

// Kimlik doğrulaması gerektiren rotalar
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth Rotaları
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Randevu Rotaları
    Route::apiResource('appointments', AppointmentController::class);
    Route::post('appointments/{appointment}/check-in', [AppointmentController::class, 'checkIn']);
    Route::post('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
    Route::post('appointments/{appointment}/call', [AppointmentController::class, 'call']);
    
    // Vaka (Encounter) Rotaları
    Route::post('encounters/{encounter}/status', [EncounterController::class, 'updateStatus']);
    Route::post('encounters/{encounter}/assign-doctor', [EncounterController::class, 'assignDoctor']);
    Route::post('encounters/{encounter}/assign-and-process', [EncounterController::class, 'assignAndProcess']);

    // Randevu Sevk Rotaları
    Route::post('appointments/{appointment}/refer', [AppointmentReferralController::class, 'store']);
    Route::post('appointments/{appointment}/accept-referral', [AppointmentReferralController::class, 'update']);
    
    // Fatura, Tedavi ve Dosya Rotaları
    Route::apiResource('invoices', InvoiceController::class)->except(['index', 'show']);
    Route::post('patient-treatments', [PatientTreatmentController::class, 'store']);
    Route::post('patients/{patient}/files', [PatientFileController::class, 'store']);
    Route::delete('files/{file}', [PatientFileController::class, 'destroy']);

    // Admin'e Özel Rotalar
    Route::prefix('admin')->middleware('can:accessAdminFeatures')->group(function () {
        Route::post('assign-patient', [PatientAssignmentController::class, 'store']);
        Route::delete('patients/{patient}/erase', [PatientErasureController::class, 'erase']);
    });
    
    // Bildirim Rotaları
    Route::get('notifications', [NotificationController::class, 'index']);
    
    // Muhasebe Rotaları
    Route::prefix('accounting')->middleware('can:accessAccountingFeatures')->group(function () {
        Route::patch('invoices/{invoice}/status', [InvoiceManagementController::class, 'updateStatus']);
        Route::patch('invoices/{invoice}/insurance', [InvoiceManagementController::class, 'updateInsurance']);
        Route::post('invoices/{invoice}/send-email', [InvoiceManagementController::class, 'sendEmail']);
        Route::get('reports/financial', [FinancialReportController::class, 'summary']);
    });
});

