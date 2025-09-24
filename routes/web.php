<?php

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AppointmentCheckinController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\WaitingRoomController;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil Rotaları
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Takvim
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/show/{appointment}', [CalendarController::class, 'show'])->name('calendar.show');
    Route::put('/calendar/{appointment}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{appointment}', [CalendarController::class, 'destroy'])->name('calendar.destroy');

    // Raporlar
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/financial-summary', [ReportController::class, 'financialSummary'])->name('financial-summary');
        Route::get('/dentist-performance', [ReportController::class, 'dentistPerformance'])->name('dentist-performance');
        Route::get('/treatment-revenue', [ReportController::class, 'treatmentRevenue'])->name('treatment-revenue');
        Route::get('/appointment-analysis', [ReportController::class, 'appointmentAnalysis'])->name('appointment-analysis');
        Route::get('/new-patient-acquisition', [ReportController::class, 'newPatientAcquisition'])->name('new-patient-acquisition');
    });

    // Hasta Yönetimi
    Route::resource('patients', PatientController::class);
    Route::put('/patients/{patient}/notes', [PatientController::class, 'updateNotes'])->name('patients.updateNotes');

    // PDF Rotaları
    Route::get('/invoices/{invoice}/pdf', [PdfController::class, 'downloadInvoice'])->name('invoices.pdf');
    Route::get('/prescriptions/{prescription}/pdf', [PdfController::class, 'downloadPrescription'])->name('prescriptions.pdf');

    // "Günün Randevuları" ve Check-in İşlemleri
    Route::get('/todays-appointments', [AppointmentCheckinController::class, 'index'])->name('appointments.today');
    Route::post('/appointments/{appointment}/check-in', [AppointmentCheckinController::class, 'checkIn'])->name('appointments.checkin');

    // Sistem Ayarları Rotaları (Sadece Admin erişebilir)
    Route::prefix('system')->name('system.')->middleware('can:accessAdminFeatures')->group(function () {
        Route::get('/', [SystemSettingsController::class, 'index'])->name('index');
        Route::get('/details', [SystemSettingsController::class, 'details'])->name('details');
        Route::post('/details', [SystemSettingsController::class, 'updateDetails'])->name('details.update');
        Route::get('/users', [SystemSettingsController::class, 'users'])->name('users.index');
        Route::get('/users/create', [SystemSettingsController::class, 'createUser'])->name('users.create');
        Route::post('/users', [SystemSettingsController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [SystemSettingsController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [SystemSettingsController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [SystemSettingsController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/backup', [SystemSettingsController::class, 'backup'])->name('backup');
        Route::post('/backup/create', [SystemSettingsController::class, 'createBackup'])->name('backup.create');
        Route::post('/backup/restore', [SystemSettingsController::class, 'restoreBackup'])->name('backup.restore');
        Route::post('/backup/delete-data', [SystemSettingsController::class, 'deleteData'])->name('backup.delete-data');
    });

    // Muhasebe Rotaları
    Route::prefix('accounting')->name('accounting.')->middleware('can:accessAccountingFeatures')->group(function () {
        Route::get('/', [AccountingController::class, 'index'])->name('index');

        // DÜZELTME: Rota, Controller'daki doğru metod adı olan 'create' metodunu işaret ediyor.
        Route::get('/new', [AccountingController::class, 'create'])->name('new');

        Route::post('/prepare', [AccountingController::class, 'prepare'])->name('prepare');
        Route::post('/', [AccountingController::class, 'store'])->name('store');

        Route::get('/invoices/{invoice}/action', [AccountingController::class, 'action'])->name('invoices.action');
        Route::put('/invoices/{invoice}', [AccountingController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{invoice}', [AccountingController::class, 'destroy'])->name('invoices.destroy');
        Route::get('/trash', [AccountingController::class, 'trash'])->name('trash');
        Route::post('/trash/{invoice}/restore', [AccountingController::class, 'restore'])->name('trash.restore');
        Route::delete('/trash/{invoice}/remove', [AccountingController::class, 'forceDelete'])->name('trash.remove');
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
