<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\WaitingRoomController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AppointmentCheckinController;
use App\Http\Controllers\AccountingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil Rotaları
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Takvim
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    
    // Raporlar
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // Hasta Yönetimi
    Route::resource('patients', PatientController::class);
    Route::put('/patients/{patient}/notes', [PatientController::class, 'updateNotes'])->name('patients.updateNotes');

    // PDF Rotaları
    Route::get('/invoices/{invoice}/pdf', [PdfController::class, 'downloadInvoice'])->name('invoices.pdf');
    Route::get('/prescriptions/{prescription}/pdf', [PdfController::class, 'downloadPrescription'])->name('prescriptions.pdf');

    // "Günün Randevuları" ve Check-in İşlemleri
    Route::get('/todays-appointments', [AppointmentCheckinController::class, 'index'])->name('appointments.today');
    Route::post('/appointments/{appointment}/check-in', [AppointmentCheckinController::class, 'checkIn'])->name('appointments.checkin');
    
    // Muhasebe Rotaları
    Route::prefix('accounting')->name('accounting.')->middleware('can:accessAccountingFeatures')->group(function () {
        Route::get('/main', [AccountingController::class, 'main'])->name('main');
        Route::get('/invoices/{invoice}/action', [AccountingController::class, 'action'])->name('invoices.action');
        Route::put('/invoices/{invoice}', [AccountingController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{invoice}', [AccountingController::class, 'destroy'])->name('invoices.destroy');
        Route::get('/trash', [AccountingController::class, 'trash'])->name('trash');
        Route::post('/trash/{id}/restore', [AccountingController::class, 'restore'])->name('trash.restore');
        Route::delete('/trash/{id}/force-delete', [AccountingController::class, 'forceDelete'])->name('trash.force-delete');
    });
    
    // Bekleme Odası Rotaları
    Route::prefix('waiting-room')->name('waiting-room.')->group(function () {
        Route::get('/', [WaitingRoomController::class, 'index'])->name('index');
        Route::get('/appointments', [WaitingRoomController::class, 'appointments'])->name('appointments');
        Route::get('/appointments/create', [WaitingRoomController::class, 'createAppointment'])->name('appointments.create');
        Route::post('/appointments', [WaitingRoomController::class, 'storeAppointment'])->name('appointments.store');
        Route::get('/appointments/search', [WaitingRoomController::class, 'searchAppointments'])->name('appointments.search');
        Route::get('/emergency', [WaitingRoomController::class, 'emergency'])->name('emergency');
        Route::get('/emergency/add', [WaitingRoomController::class, 'createEmergency'])->name('emergency.create');
        Route::post('/emergency', [WaitingRoomController::class, 'storeEmergency'])->name('emergency.store');
        Route::get('/completed', [WaitingRoomController::class, 'completed'])->name('completed');
        Route::get('/{encounter}/action', [WaitingRoomController::class, 'action'])->name('action');
        Route::put('/{encounter}/action', [WaitingRoomController::class, 'updateAction'])->name('action.update');
    });
});

require __DIR__.'/auth.php';

