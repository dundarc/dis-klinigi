<?php

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\Api\V1\AppointmentController as ApiAppointmentController;
use App\Http\Controllers\Api\V1\EncounterController as ApiEncounterController;
use App\Http\Controllers\Api\V1\PatientController as ApiPatientController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WaitingRoomController;
use Illuminate\Support\Facades\Route;

Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

// Yeni röntgen görseli yükleme rotası (POST)
Route::post('/patients/{patient}/xrays', [PatientController::class, 'storeXRay'])->name('patients.x-rays.store');


// Hasta detay sayfası
Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

// Yeni tedavi ekleme formunu göstermek için (GET)
Route::get('/patients/{patient}/treatments/create', [PatientController::class, 'createTreatment'])->name('patients.treatments.create');

// Yeni tedavi formundan gelen veriyi kaydetmek için (POST)
Route::post('/patients/{patient}/treatments', [PatientController::class, 'storeTreatment'])->name('patients.treatments.store');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/waiting-room', [WaitingRoomController::class, 'index'])->name('waiting-room');

    Route::prefix('waiting-room')->name('waiting-room.')->group(function () {
        Route::post('appointments', [ApiAppointmentController::class, 'store'])->name('appointments.store');
        Route::post('appointments/{appointment}/call', [ApiAppointmentController::class, 'call'])->name('appointments.call');
        Route::post('appointments/{appointment}/status', [ApiAppointmentController::class, 'updateStatus'])->name('appointments.status');
        Route::post('appointments/{appointment}/check-in', [ApiAppointmentController::class, 'checkIn'])->name('appointments.check-in');
        Route::get('dentists/{dentist}/schedule', [ApiAppointmentController::class, 'dentistSchedule'])->name('dentists.schedule');

        Route::post('encounters', [ApiEncounterController::class, 'store'])->name('encounters.store');
        Route::post('encounters/{encounter}/assign-and-process', [ApiEncounterController::class, 'assignAndProcess'])->name('encounters.assign');
        Route::post('encounters/{encounter}/status', [ApiEncounterController::class, 'updateStatus'])->name('encounters.status');

        Route::get('patients/search', [ApiPatientController::class, 'search'])->name('patients.search');
        Route::post('patients', [ApiPatientController::class, 'store'])->name('patients.store');
    });

    Route::post('encounters/{encounter}/status', [ApiEncounterController::class, 'updateStatus']);
    Route::post('encounters/{encounter}/assign-doctor', [ApiEncounterController::class, 'assignDoctor']);
    Route::get('/invoices/{invoice}/pdf', [PdfController::class, 'downloadInvoice'])->name('invoices.pdf');
    Route::get('/prescriptions/{prescription}/pdf', [PdfController::class, 'downloadPrescription'])->name('prescriptions.pdf');
 
     Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

     Route::resource('patients', PatientController::class);
    Route::get('/accounting', [AccountingController::class, 'index'])->middleware('can:accessAccountingFeatures')->name('accounting');

});

require __DIR__.'/auth.php';