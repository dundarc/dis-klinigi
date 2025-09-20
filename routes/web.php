<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController; // Ekleyin
use App\Http\Controllers\WaitingRoomController; // Ekleyin
use App\Http\Controllers\Api\V1\EncounterController; // Ekleyin
use App\Http\Controllers\PdfController; // Ekleyin
use App\Http\Controllers\ReportController; // Ekleyin

use App\Http\Controllers\PatientController; // Ekleyin

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
      Route::post('encounters/{encounter}/status', [EncounterController::class, 'updateStatus']);
    Route::post('encounters/{encounter}/assign-doctor', [EncounterController::class, 'assignDoctor']);
     Route::get('/invoices/{invoice}/pdf', [PdfController::class, 'downloadInvoice'])->name('invoices.pdf');
    Route::get('/prescriptions/{prescription}/pdf', [PdfController::class, 'downloadPrescription'])->name('prescriptions.pdf');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
        Route::resource('patients', PatientController::class);
});

require __DIR__.'/auth.php';
