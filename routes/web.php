<?php

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AppointmentCheckinController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientFileController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Stock\StockDashboardController;
use App\Http\Controllers\Stock\StockItemController;
use App\Http\Controllers\Stock\StockCategoryController;
use App\Http\Controllers\Stock\StockSupplierController;
use App\Http\Controllers\Stock\StockPurchaseController;
use App\Http\Controllers\Stock\StockExpenseController;
use App\Http\Controllers\Stock\StockUsageController;
use App\Http\Controllers\Stock\StockCurrentAccountController;
use App\Http\Controllers\Stock\StockReportController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\SystemTreatmentController;
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
    // Profil RotalarÄ±
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Takvim
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/today', [CalendarController::class, 'today'])->name('calendar.today');
    Route::get('/calendar/show/{appointment}', [CalendarController::class, 'show'])->name('calendar.show');
    Route::put('/calendar/{appointment}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{appointment}', [CalendarController::class, 'destroy'])->name('calendar.destroy');

    // Raporlar
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/stock', [StockReportController::class, 'index'])->name('stock')->middleware('can:viewStockReports');
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/financial-summary', [ReportController::class, 'financialSummary'])->name('financial-summary');
        Route::get('/financial-summary/pdf', [ReportController::class, 'financialSummaryPdf'])->name('financial-summary.pdf');
        Route::get('/dentist-performance', [ReportController::class, 'dentistPerformance'])->name('dentist-performance');
        Route::get('/treatment-revenue', [ReportController::class, 'treatmentRevenue'])->name('treatment-revenue');
        Route::get('/appointment-analysis', [ReportController::class, 'appointmentAnalysis'])->name('appointment-analysis');
        Route::get('/new-patient-acquisition', [ReportController::class, 'newPatientAcquisition'])->name('new-patient-acquisition');
    });

    // Bildirimler
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/send', [NotificationController::class, 'createSend'])->name('send.create')->middleware('can:sendNotifications');
        Route::post('/send', [NotificationController::class, 'storeSend'])->name('send.store')->middleware('can:sendNotifications');
        Route::get('/delivered', [NotificationController::class, 'delivered'])->name('delivered')->middleware('can:sendNotifications');
        Route::patch('/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::patch('/{notification}/mark-unread', [NotificationController::class, 'markAsUnread'])->name('markAsUnread');
        Route::patch('/{notification}/mark-completed', [NotificationController::class, 'markAsCompleted'])->name('markAsCompleted');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // Hasta YÃ¶netimi
    Route::resource('patients', PatientController::class);
    Route::put('/patients/{patient}/notes', [PatientController::class, 'updateNotes'])->name('patients.updateNotes');
    Route::get('/patient-files/{file}', [PatientFileController::class, 'show'])->name('patient-files.show');

    // PDF RotalarÄ±
    Route::get('/invoices/{invoice}/pdf', [PdfController::class, 'downloadInvoice'])->name('invoices.pdf');
    Route::get('/prescriptions/{prescription}/pdf', [PdfController::class, 'downloadPrescription'])->name('prescriptions.pdf');

    // "GÃ¼nÃ¼n RandevularÄ±" ve Check-in Ä°ÅŸlemleri
    Route::get('/todays-appointments', [AppointmentCheckinController::class, 'index'])->name('appointments.today');
    Route::post('/appointments/{appointment}/check-in', [AppointmentCheckinController::class, 'checkIn'])->name('appointments.checkin');

    // Sistem AyarlarÄ± RotalarÄ± (Sadece Admin eriÅŸebilir)
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

        Route::prefix('treatments')->name('treatments.')->group(function () {
            Route::get('/', [SystemTreatmentController::class, 'index'])->name('index');
            Route::get('/add', [SystemTreatmentController::class, 'create'])->name('create');
            Route::post('/', [SystemTreatmentController::class, 'store'])->name('store');
            Route::get('/{treatment}/action', [SystemTreatmentController::class, 'edit'])->name('edit');
            Route::put('/{treatment}', [SystemTreatmentController::class, 'update'])->name('update');
            Route::delete('/{treatment}', [SystemTreatmentController::class, 'destroy'])->name('destroy');
        });
    });
    // Stok Rotalar�
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::middleware('can:accessStockManagement')->group(function () {
            Route::get('/', [StockDashboardController::class, 'index'])->name('dashboard');
            Route::resource('items', StockItemController::class)->except(['show']);
            Route::resource('categories', StockCategoryController::class)->only(['index', 'store', 'update', 'destroy']);
            Route::resource('suppliers', StockSupplierController::class)->except(['show']);
            Route::resource('purchases', StockPurchaseController::class);
            Route::resource('expenses', StockExpenseController::class);
            Route::get('/usage', [StockUsageController::class, 'index'])->name('usage.index');
            Route::get('/current', [StockCurrentAccountController::class, 'index'])->name('current.index');
            Route::get('/current/{supplier}', [StockCurrentAccountController::class, 'show'])->name('current.show');
        });

        Route::middleware('can:recordStockUsage')->group(function () {
            Route::get('/usage/create', [StockUsageController::class, 'create'])->name('usage.create');
            Route::post('/usage', [StockUsageController::class, 'store'])->name('usage.store');
        });
    });

    // Muhasebe RotalarÄ±
    Route::prefix('accounting')->name('accounting.')->middleware('can:accessAccountingFeatures')->group(function () {
        Route::get('/', [AccountingController::class, 'index'])->name('index');
    Route::get('/search', [AccountingController::class, 'search'])->name('search');
    Route::post('/update', [AccountingController::class, 'updateOverdueInvoices'])->name('update');

        // DÃœZELTME: Rota, Controller'daki doÄŸru metod adÄ± olan 'create' metodunu iÅŸaret ediyor.
        Route::get('/new', [AccountingController::class, 'create'])->name('new');

        Route::post('/prepare', [AccountingController::class, 'prepare'])->name('prepare');
        Route::post('/', [AccountingController::class, 'store'])->name('store');

        Route::get('/invoices/{invoice}/action', [AccountingController::class, 'action'])->name('invoices.action');
        Route::post('/invoices/{invoice}/items', [AccountingController::class, 'addItem'])->name('invoices.items.store');
        Route::put('/invoices/{invoice}/items/{item}', [AccountingController::class, 'updateItem'])->name('invoices.items.update');
        Route::delete('/invoices/{invoice}/items/{item}', [AccountingController::class, 'destroyItem'])->name('invoices.items.destroy');
        Route::put('/invoices/{invoice}', [AccountingController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{invoice}', [AccountingController::class, 'destroy'])->name('invoices.destroy');
        Route::get('/trash', [AccountingController::class, 'trash'])->name('trash');
        Route::post('/trash/{invoice}/restore', [AccountingController::class, 'restore'])->name('trash.restore');
        Route::delete('/trash/{invoice}/remove', [AccountingController::class, 'forceDelete'])->name('trash.remove');
    });

    // Bekleme OdasÄ± RotalarÄ±
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







