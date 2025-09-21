<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\EncounterController; // Bu satırı da eklemiştik
use App\Http\Controllers\Api\V1\AppointmentReferralController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\PatientTreatmentController;

use App\Http\Controllers\Api\V1\Admin\PatientAssignmentController;
use App\Http\Controllers\Api\V1\NotificationController;

use App\Http\Controllers\Api\V1\Accounting\InvoiceManagementController;
use App\Http\Controllers\Api\V1\Accounting\FinancialReportController;





use App\Http\Controllers\Api\V1\PatientController;






use App\Http\Controllers\Api\V1\Admin\PatientErasureController;

// Herkese açık rotalar
Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
});

// Kimlik doğrulaması gerektiren rotalar
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('appointments/{appointment}/check-in', [AppointmentController::class, 'checkIn']);
    Route::post('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
    Route::post('appointments/{appointment}/call', [AppointmentController::class, 'call']); // BU SATIRI EKLEYİN
    Route::post('encounters/{encounter}/assign-and-process', [EncounterController::class, 'assignAndProcess']);
    Route::get('dentists/{dentist}/schedule', [AppointmentController::class, 'dentistSchedule']);
    Route::post('encounters', [EncounterController::class, 'store']);

    Route::prefix('accounting')->middleware('can:accessAccountingFeatures')->group(function () {
        Route::patch('invoices/{invoice}/status', [InvoiceManagementController::class, 'updateStatus']);
        Route::patch('invoices/{invoice}/insurance', [InvoiceManagementController::class, 'updateInsurance']);
        Route::post('invoices/{invoice}/send-email', [InvoiceManagementController::class, 'sendEmail']);
        Route::get('reports/financial', [FinancialReportController::class, 'summary']);
  });


    // Appointments (Resourceful Controller kullanarak)
    // Bu satır GET, POST, PUT, DELETE rotalarını otomatik oluşturur
    Route::apiResource('appointments', AppointmentController::class);
    
    // Appointments için özel aksiyonlar
    Route::post('appointments/{appointment}/check-in', [AppointmentController::class, 'checkIn']);
    // Adım 7'de eklediğimiz status güncelleme rotası (JS kodunda kullanılıyor)
    Route::post('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']); // Bu metodun controller'da olması gerekir.

    // Encounters için rotalar
    Route::post('encounters/{encounter}/status', [EncounterController::class, 'updateStatus']);
    Route::post('encounters/{encounter}/assign-doctor', [EncounterController::class, 'assignDoctor']);

     // Randevu Sevk Rotaları
    Route::post('appointments/{appointment}/refer', [AppointmentReferralController::class, 'store']);
    Route::post('appointments/{appointment}/accept-referral', [AppointmentReferralController::class, 'update']);
    
    // Fatura ve Hasta Tedavi Rotaları (şimdilik sadece oluşturma)
    Route::post('invoices', [InvoiceController::class, 'store']);
    Route::post('patient-treatments', [PatientTreatmentController::class, 'store']);


     // Admin'e özel rotalar
    Route::prefix('admin')->middleware('can:accessAdminFeatures')->group(function () {
        Route::post('assign-patient', [PatientAssignmentController::class, 'store']);
            Route::delete('patients/{patient}/erase', [PatientErasureController::class, 'erase']);
    });
    
    // Tüm kullanıcılar için bildirim rotaları
    Route::get('notifications', [NotificationController::class, 'index']);


});