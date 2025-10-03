<?php

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\AiSystemController;
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
use App\Http\Controllers\Installation\InstallationController;

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
    // Eğer sistem kurulu değilse kurulum sayfasına yönlendir
    if (!file_exists(storage_path('installed'))) {
        return redirect('/install');
    }
    return view('welcome');
});

// Installation Routes
Route::group(['prefix' => 'install', 'middleware' => 'install'], function () {
    Route::get('/', [InstallationController::class, 'welcome'])->name('installation.welcome');
    Route::get('/requirements', [InstallationController::class, 'requirements'])->name('installation.requirements');
    Route::get('/database', [InstallationController::class, 'database'])->name('installation.database');
    Route::post('/database', [InstallationController::class, 'setupDatabase'])->name('installation.database.setup');
    Route::get('/clinic', [InstallationController::class, 'clinic'])->name('installation.clinic');
    Route::post('/clinic', [InstallationController::class, 'saveClinic'])->name('installation.clinic.save');
    Route::get('/admin', [InstallationController::class, 'admin'])->name('installation.admin');
    Route::post('/admin', [InstallationController::class, 'createAdmin'])->name('installation.admin.create');
    Route::get('/complete', [InstallationController::class, 'complete'])->name('installation.complete');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/quick-actions', function () {
    return view('dashboard.quick-actions');
})->middleware(['auth', 'verified'])->name('dashboard.quick-actions');

Route::get('/help', function () {
    return view('help');
})->middleware(['auth', 'verified'])->name('help');

Route::get('/help/dashboard', function () {
    return view('help.dashboard');
})->middleware(['auth', 'verified'])->name('help.dashboard');

Route::get('/help/calendar', function () {
    return view('help.calendar');
})->middleware(['auth', 'verified'])->name('help.calendar');

Route::get('/help/patients', function () {
    return view('help.patients');
})->middleware(['auth', 'verified'])->name('help.patients');

Route::get('/help/kvkk', function () {
    return view('help.kvkk');
})->middleware(['auth', 'verified'])->name('help.kvkk');

Route::get('/help/waiting-room', function () {
    return view('help.waiting-room');
})->middleware(['auth', 'verified'])->name('help.waiting-room');

Route::get('/help/stock', function () {
    return view('help.stock');
})->middleware(['auth', 'verified'])->name('help.stock');

Route::get('/help/reports', function () {
    return view('help.reports');
})->middleware(['auth', 'verified'])->name('help.reports');

Route::get('/help/accounting', function () {
    return view('help.accounting');
})->middleware(['auth', 'verified'])->name('help.accounting');

Route::get('/help/notifications', function () {
    return view('help.notifications');
})->middleware(['auth', 'verified'])->name('help.notifications');

Route::get('/help/system', function () {
    return view('help.system');
})->middleware(['auth', 'verified'])->name('help.system');

Route::get('/help/ai', function () {
    return view('help.ai');
})->middleware(['auth', 'verified'])->name('help.ai');

Route::get('/help/profile', function () {
    return view('help.profile');
})->middleware(['auth', 'verified'])->name('help.profile');

Route::get('/help/patients', function () {
    return view('help.patients');
})->middleware(['auth', 'verified'])->name('help.patients');

Route::get('/help/calendar', function () {
    return view('help.calendar');
})->middleware(['auth', 'verified'])->name('help.calendar');

Route::get('/help/treatment-plans', function () {
    return view('help.treatment-plans');
})->middleware(['auth', 'verified'])->name('help.treatment-plans');

Route::get('/help/kvkk', function () {
    return view('help.kvkk');
})->middleware(['auth', 'verified'])->name('help.kvkk');

Route::get('/search', [DashboardController::class, 'search'])
    ->middleware(['auth', 'verified'])
    ->name('search');

Route::middleware('auth')->group(function () {
    // Profil RotalarÄ±
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Takvim
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/week', [CalendarController::class, 'week'])->name('calendar.week');
    Route::get('/calendar/day', [CalendarController::class, 'day'])->name('calendar.day');
    Route::get('/calendar/export', [CalendarController::class, 'export'])->name('calendar.export');
    Route::get('/calendar/today', [CalendarController::class, 'today'])->name('calendar.today');
    Route::get('/calendar/show/{appointment}', [CalendarController::class, 'show'])->name('calendar.show');
    Route::put('/calendar/{appointment}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{appointment}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    Route::post('/appointments/{appointment}/attach-items', [CalendarController::class, 'attachItems'])->name('appointments.attachItems');

    // Raporlar
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/stock', [StockReportController::class, 'index'])->name('stock')->middleware('can:viewStockReports');
        Route::get('/stock/monthly-expenses', [StockReportController::class, 'monthlyExpenseReport'])->name('stock.monthly-expenses')->middleware('can:viewStockReports');
        Route::get('/stock/service-expenses', [StockReportController::class, 'serviceExpenseReport'])->name('stock.service-expenses')->middleware('can:viewStockReports');
        Route::get('/stock/current-account', [StockReportController::class, 'currentAccountReport'])->name('stock.current-account')->middleware('can:viewStockReports');
        Route::get('/stock/supplier-report', [StockReportController::class, 'supplierReport'])->name('stock.supplier-report')->middleware('can:viewStockReports');
        Route::get('/stock/critical-stock', [StockReportController::class, 'criticalStockReport'])->name('stock.critical-stock')->middleware('can:viewStockReports');
        Route::get('/stock/overdue-invoices', [StockReportController::class, 'overdueInvoicesReport'])->name('stock.overdue-invoices')->middleware('can:viewStockReports');

        // Export routes
        Route::get('/stock/monthly-expenses/export/excel', [StockReportController::class, 'exportMonthlyExpenses'])->name('stock.monthly-expenses.export.excel')->middleware('can:viewStockReports');
        Route::get('/stock/monthly-expenses/export/pdf', [StockReportController::class, 'exportMonthlyExpensesPdf'])->name('stock.monthly-expenses.export.pdf')->middleware('can:viewStockReports');
        Route::get('/stock/monthly-expenses/print', [StockReportController::class, 'printMonthlyExpenses'])->name('stock.monthly-expenses.print')->middleware('can:viewStockReports');

        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/financial-summary', [ReportController::class, 'financialSummary'])->name('financial-summary');
        Route::get('/financial-summary/pdf', [ReportController::class, 'financialSummaryPdf'])->name('financial-summary.pdf');
        Route::get('/dentist-performance', [ReportController::class, 'dentistPerformance'])->name('dentist-performance');
        Route::get('/dentist-performance/pdf', [ReportController::class, 'dentistPerformancePdf'])->name('dentist-performance.pdf');
        Route::get('/dentist-performance/export/excel', [ReportController::class, 'exportDentistPerformance'])->name('dentist-performance.export.excel');
        Route::get('/treatment-revenue', [ReportController::class, 'treatmentRevenue'])->name('treatment-revenue');
        Route::get('/treatment-revenue/pdf', [ReportController::class, 'treatmentRevenuePdf'])->name('treatment-revenue.pdf');
        Route::get('/treatment-revenue/export/excel', [ReportController::class, 'exportTreatmentRevenue'])->name('treatment-revenue.export.excel');
        Route::get('/appointment-analysis', [ReportController::class, 'appointmentAnalysis'])->name('appointment-analysis');
        Route::get('/appointment-analysis/pdf', [ReportController::class, 'appointmentAnalysisPdf'])->name('appointment-analysis.pdf');
        Route::get('/appointment-analysis/export/excel', [ReportController::class, 'exportAppointmentAnalysis'])->name('appointment-analysis.export.excel');
        Route::get('/new-patient-acquisition', [ReportController::class, 'newPatientAcquisition'])->name('new-patient-acquisition');
        Route::get('/new-patient-acquisition/pdf', [ReportController::class, 'newPatientAcquisitionPdf'])->name('new-patient-acquisition.pdf');
        Route::get('/new-patient-acquisition/export/excel', [ReportController::class, 'exportNewPatientAcquisition'])->name('new-patient-acquisition.export.excel');
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

    // Search endpoints for quick actions
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/patients', [\App\Http\Controllers\SearchController::class, 'patients'])->name('patients');
        Route::get('/appointments', [\App\Http\Controllers\SearchController::class, 'appointments'])->name('appointments');
        Route::get('/treatment-plans', [\App\Http\Controllers\SearchController::class, 'treatmentPlans'])->name('treatment-plans');
        Route::get('/suppliers', [\App\Http\Controllers\SearchController::class, 'suppliers'])->name('suppliers');
    });

    // Hasta YÃ¶netimi
    Route::get('/patients/search', [PatientController::class, 'search'])
        ->middleware('can:viewAny,App\\Models\\Patient')
        ->name('patients.search');
    Route::get('/dentists/search', [PatientController::class, 'searchDentists'])
        ->middleware('can:viewAny,App\\Models\\Patient')
        ->name('dentists.search');
    Route::get('/suppliers/search', [PatientController::class, 'searchSuppliers'])
        ->middleware('can:accessStockManagement')
        ->name('suppliers.search');
    Route::resource('patients', PatientController::class);
    Route::put('/patients/{patient}/notes', [PatientController::class, 'updateNotes'])->name('patients.updateNotes');
    Route::get('/patient-files/{file}', [PatientFileController::class, 'show'])->name('patient-files.show');

    // Hasta KVKK Ä°ÅŸlemleri
    Route::prefix('patients/kvkk')->name('patients.kvkk.')->group(function () {
        Route::get('/reports/missing', [\App\Http\Controllers\PatientKvkkController::class, 'missingConsents'])->name('reports.missing');
        Route::get('/{patient}', [\App\Http\Controllers\PatientKvkkController::class, 'showConsentForm'])->name('consent');
        Route::post('/{patient}/consent', [\App\Http\Controllers\PatientKvkkController::class, 'storeConsent'])->name('store-consent');
        Route::get('/{patient}/details', [\App\Http\Controllers\PatientKvkkController::class, 'show'])->name('show');
    });

    // Tedavi planlarÄ±
    Route::get('patients/{patient}/treatment-plans/create', [\App\Http\Controllers\TreatmentPlanController::class, 'create'])->name('patients.treatment-plans.create');
    Route::get('treatment-plans/all', [\App\Http\Controllers\TreatmentPlanController::class, 'all'])->name('treatment-plans.all');
    Route::get('treatment-plans/search', [\App\Http\Controllers\TreatmentPlanController::class, 'all'])->name('treatment-plans.search');
    Route::resource('treatment-plans', \App\Http\Controllers\TreatmentPlanController::class)->except(['create']);
    Route::get('treatment-plans/{treatmentPlan}/pdf', [\App\Http\Controllers\TreatmentPlanController::class, 'downloadPdf'])->name('treatment-plans.pdf')->withoutMiddleware('auth');
    Route::get('treatment-plans/{treatment_plan}/cost-report', [\App\Http\Controllers\TreatmentPlanController::class, 'costReport'])->name('treatment-plans.cost-report');
    Route::post('treatment-plans/{treatment_plan}/items', [\App\Http\Controllers\QuickActionsController::class, 'addTreatmentPlanItem'])->name('treatment-plans.items.store');
    Route::post('treatment-plans/{treatmentPlan}/autosave', [\App\Http\Controllers\TreatmentPlanController::class, 'autosave'])->name('treatment-plans.autosave');
    Route::post('appointments/{appointment}/link-items', [\App\Http\Controllers\QuickActionsController::class, 'linkItemsToAppointment'])->name('appointments.link-items');

    // API Routes - Treatment Plans
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('treatment-plans/{treatmentPlan}', function (\App\Models\TreatmentPlan $treatmentPlan) {
            return response()->json([
                'id' => $treatmentPlan->id,
                'patient_id' => $treatmentPlan->patient_id,
                'dentist_id' => $treatmentPlan->dentist_id,
                'status' => $treatmentPlan->status->value,
                'notes' => $treatmentPlan->notes,
                'total_estimated_cost' => $treatmentPlan->total_estimated_cost,
            ]);
        })->name('treatment-plans.show');

        Route::get('treatment-plans/{treatmentPlan}/items', function (\App\Models\TreatmentPlan $treatmentPlan) {
            $items = $treatmentPlan->items()->with('treatment')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'treatment_id' => $item->treatment_id,
                    'treatment_name' => $item->treatment->name,
                    'tooth_number' => $item->tooth_number,
                    'appointment_date' => $item->appointment?->start_at?->format('Y-m-d\TH:i'),
                    'estimated_price' => $item->estimated_price,
                    'status' => $item->status->value,
                ];
            });
            return response()->json($items);
        })->name('treatment-plans.items');
    });

    // Quick Actions
    Route::prefix('quick-actions')->name('quick-actions.')->group(function () {
        Route::post('/upload-file', [\App\Http\Controllers\QuickActionsController::class, 'uploadFile'])->name('upload-file');
        Route::post('/cancel-appointment/{appointment}', [\App\Http\Controllers\QuickActionsController::class, 'cancelAppointment'])->name('cancel-appointment');
        Route::patch('/update-patient/{patient}', [\App\Http\Controllers\QuickActionsController::class, 'updatePatient'])->name('update-patient');
        Route::post('/create-stock-item', [\App\Http\Controllers\QuickActionsController::class, 'createStockItem'])->name('create-stock-item');
        Route::post('/create-invoice', [\App\Http\Controllers\QuickActionsController::class, 'createInvoice'])->name('create-invoice');
        Route::post('/create-payment', [\App\Http\Controllers\QuickActionsController::class, 'createPayment'])->name('create-payment');
    });


    // PDF RotalarÃ„Â±
    Route::get('/invoices/{invoice}/pdf', [PdfController::class, 'downloadInvoice'])->name('invoices.pdf');
    Route::get('/prescriptions/{prescription}/pdf', [PdfController::class, 'downloadPrescription'])->name('prescriptions.pdf');
    Route::get('/encounters/{encounter}/pdf', [PdfController::class, 'downloadEncounter'])->name('encounters.pdf');

    // "GÃƒÂ¼nÃƒÂ¼n RandevularÃ„Â±" ve Check-in Ã„Â°Ã…Å¸lemleri
    Route::get('/todays-appointments', [AppointmentCheckinController::class, 'index'])->name('appointments.today');
    Route::post('/appointments/{appointment}/check-in', [AppointmentCheckinController::class, 'checkIn'])->name('appointments.checkin');
    Route::post('/appointments/{appointment}/no-show', [AppointmentCheckinController::class, 'markNoShow'])->name('appointments.no-show');

    // AI Routes
    Route::get('/ai', [AiController::class, 'index'])->name('ai.index');
    Route::post('/ai', [AiController::class, 'chat'])->name('ai.chat');

    // Sistem AyarlarÃ„Â± RotalarÃ„Â± (Sadece Admin eriÃ…Å¸ebilir)
    Route::prefix('system')->name('system.')->middleware('can:accessAdminFeatures')->group(function () {
        // Yedekleme iÃ…Å¸lemleri
        Route::get('/backup/download/{filename}', [SystemSettingsController::class, 'downloadBackup'])->name('backup.download');
        Route::get('/backup/destroy-data', [SystemSettingsController::class, 'destroyData'])->name('backup.destroy-data');
        Route::post('/backup/destroy-data', [SystemSettingsController::class, 'destroyDataConfirm'])->name('backup.destroy-data.confirm');
        Route::post('/backup/upload', [SystemSettingsController::class, 'uploadBackup'])->name('backup.upload');
        Route::post('/backup/restore-file', [SystemSettingsController::class, 'restoreFromFile'])->name('backup.restore-file');
        Route::get('/backup/available', [SystemSettingsController::class, 'getAvailableBackups'])->name('backup.available');
        Route::get('/', [SystemSettingsController::class, 'index'])->name('index');
        Route::get('/details', [SystemSettingsController::class, 'details'])->name('details');
        Route::post('/details', [SystemSettingsController::class, 'updateDetails'])->name('details.update');
        Route::get('/ai', [AiSystemController::class, 'index'])->name('ai.index');
        Route::put('/ai', [AiSystemController::class, 'update'])->name('ai.update');
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
        Route::get('/trash-docs', [\App\Http\Controllers\SystemTrashController::class, 'index'])->name('trash-docs.index');
        Route::patch('/trash-docs/{file}/restore', [\App\Http\Controllers\SystemTrashController::class, 'restore'])->name('trash-docs.restore');
        Route::delete('/trash-docs/{file}/force-delete', [\App\Http\Controllers\SystemTrashController::class, 'forceDelete'])->name('trash-docs.force-delete');
        Route::post('/trash-docs/bulk-restore', [\App\Http\Controllers\SystemTrashController::class, 'bulkRestore'])->name('trash-docs.bulk-restore');
        Route::post('/trash-docs/bulk-force-delete', [\App\Http\Controllers\SystemTrashController::class, 'bulkForceDelete'])->name('trash-docs.bulk-force-delete');

        Route::prefix('treatments')->name('treatments.')->group(function () {
            Route::get('/', [SystemTreatmentController::class, 'index'])->name('index');
            Route::get('/add', [SystemTreatmentController::class, 'create'])->name('create');
            Route::post('/', [SystemTreatmentController::class, 'store'])->name('store');
            Route::get('/{treatment}/action', [SystemTreatmentController::class, 'edit'])->name('edit');
            Route::put('/{treatment}', [SystemTreatmentController::class, 'update'])->name('update');
            Route::delete('/{treatment}', [SystemTreatmentController::class, 'destroy'])->name('destroy');

            // KDV Management Routes
            Route::post('/bulk-update-vat', [SystemTreatmentController::class, 'bulkUpdateVat'])->name('bulk-update-vat');
            Route::post('/set-medical-vat-rate', [SystemTreatmentController::class, 'setMedicalVatRate'])->name('set-medical-vat-rate');
        });

        Route::prefix('email')->name('email.')->group(function () {
            // Ana sayfa - Dashboard
            Route::get('/', [\App\Http\Controllers\System\EmailController::class, 'dashboard'])->name('dashboard');

            // SMTP AyarlarÄ±
            Route::get('/configure', [\App\Http\Controllers\System\EmailController::class, 'index'])->name('index');
            Route::post('/configure', [\App\Http\Controllers\System\EmailController::class, 'update'])->name('update');
            Route::post('/test', [\App\Http\Controllers\System\EmailController::class, 'test'])->name('test');

            // Åablonlar
            Route::get('/templates', [\App\Http\Controllers\System\EmailTemplateController::class, 'index'])->name('templates.index');
            Route::get('/templates/create', [\App\Http\Controllers\System\EmailTemplateController::class, 'create'])->name('templates.create');
            Route::post('/templates', [\App\Http\Controllers\System\EmailTemplateController::class, 'store'])->name('templates.store');
            Route::get('/templates/{template}/edit', [\App\Http\Controllers\System\EmailTemplateController::class, 'edit'])->name('templates.edit');
            Route::put('/templates/{template}', [\App\Http\Controllers\System\EmailTemplateController::class, 'update'])->name('templates.update');
            Route::delete('/templates/{template}', [\App\Http\Controllers\System\EmailTemplateController::class, 'destroy'])->name('templates.destroy');

            // Loglar
            Route::get('/logs', [\App\Http\Controllers\System\EmailLogController::class, 'index'])->name('logs.index');
            Route::get('/logs/{log}', [\App\Http\Controllers\System\EmailLogController::class, 'show'])->name('logs.show');

            // Ä°statistik
            Route::get('/stats', [\App\Http\Controllers\System\EmailStatsController::class, 'index'])->name('stats.index');

            // Bounce yÃ¶netimi
            Route::get('/bounces', [\App\Http\Controllers\System\EmailBounceController::class, 'index'])->name('bounces.index');

            // Bounce webhook (provider-agnostic)
            Route::post('/webhooks/bounce', [\App\Http\Controllers\System\EmailWebhookController::class, 'bounce'])->name('webhooks.bounce');
        });
    });
    // Stok RotalarÄ±
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::middleware('can:accessStockManagement')->group(function () {
            Route::get('/', [StockDashboardController::class, 'index'])->name('dashboard');
            Route::resource('items', StockItemController::class);
            Route::post('/items/{item}/add-movement', [StockItemController::class, 'addMovement'])->name('items.add-movement');
            Route::post('/items/bulk-movement', [StockItemController::class, 'bulkMovement'])->name('items.bulk-movement');
            Route::get('/items/search', [StockItemController::class, 'search'])->name('items.search');
            Route::get('/items/export/excel', [StockItemController::class, 'exportExcel'])->name('items.export.excel');
            Route::get('/items/export/pdf', [StockItemController::class, 'exportPdf'])->name('items.export.pdf');
            Route::get('/items/print', [StockItemController::class, 'print'])->name('items.print');
            Route::resource('categories', StockCategoryController::class);
            Route::resource('suppliers', StockSupplierController::class)->except(['show']);
            Route::resource('purchases', StockPurchaseController::class);
            Route::post('purchases/{purchase}/payments', [StockPurchaseController::class, 'addPayment'])->name('purchases.addPayment');
            Route::post('purchases/{purchase}/installment-schedule', [StockPurchaseController::class, 'createInstallmentSchedule'])->name('purchases.create-installment-schedule');
            Route::post('purchases/batch-upload', [StockPurchaseController::class, 'batchUpload'])->name('purchases.batch-upload');
            Route::post('purchases/ocr-process', [StockPurchaseController::class, 'processOcr'])->name('purchases.ocr-process');
            Route::get('purchases/suggest-items', [StockPurchaseController::class, 'suggestItems'])->name('purchases.suggest-items');
            Route::resource('expenses', StockExpenseController::class);
            Route::resource('expense-categories', \App\Http\Controllers\Stock\StockExpenseCategoryController::class);
            Route::get('/usage', [StockUsageController::class, 'index'])->name('usage.index');
            Route::get('/current', [StockCurrentAccountController::class, 'index'])->name('current.index');
            Route::get('/current/{supplier}', [StockCurrentAccountController::class, 'show'])->name('current.show');
        });

        Route::middleware('can:recordStockUsage')->group(function () {
            Route::get('/usage/create', [StockUsageController::class, 'create'])->name('usage.create');
            Route::post('/usage', [StockUsageController::class, 'store'])->name('usage.store');
        });

        // Stock Movement Routes
        Route::middleware('can:accessStockManagement')->group(function () {
            Route::get('/movements', [\App\Http\Controllers\Stock\StockMovementController::class, 'index'])->name('movements.index');
            Route::get('/movements/export/pdf', [\App\Http\Controllers\Stock\StockMovementController::class, 'exportPdf'])->name('movements.export.pdf');
            Route::get('/movements/print', [\App\Http\Controllers\Stock\StockMovementController::class, 'print'])->name('movements.print');
            Route::get('/movements/critical', [\App\Http\Controllers\Stock\StockMovementController::class, 'critical'])->name('movements.critical');
            Route::get('/movements/item/{item}', [\App\Http\Controllers\Stock\StockMovementController::class, 'itemHistory'])->name('movements.item-history');
            Route::get('/movements/create-adjustment', [\App\Http\Controllers\Stock\StockMovementController::class, 'createAdjustment'])->name('movements.create-adjustment');
            Route::post('/movements/store-adjustment', [\App\Http\Controllers\Stock\StockMovementController::class, 'storeAdjustment'])->name('movements.store-adjustment');

            // Bulk Movements
            Route::get('/bulk-movements', [\App\Http\Controllers\Stock\StockMovementController::class, 'bulkMovements'])->name('bulk-movements');
            Route::post('/bulk-movements', [\App\Http\Controllers\Stock\StockMovementController::class, 'storeBulkMovements'])->name('bulk-movements.store');

            // Recent Movements PDF Export
            Route::get('/movements/export/recent-pdf', [\App\Http\Controllers\Stock\StockMovementController::class, 'exportRecentMovementsPdf'])->name('movements.export.recent-pdf');

            // Recent Bulk Operations API
            Route::get('/bulk-operations/recent', [\App\Http\Controllers\Stock\StockMovementController::class, 'getRecentBulkOperations'])->name('bulk-operations.recent');
            Route::get('/bulk-operations/{batchId}/export-pdf', [\App\Http\Controllers\Stock\StockMovementController::class, 'exportBulkOperationPdf'])->name('bulk-operations.export.pdf');
        });
    });

    // Log Viewer - Sadece Admin
  //  Route::get('/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']) ->middleware('can:accessAdminFeatures') ->name('logs');

    // Muhasebe RotalarÃ„Â±
    Route::prefix('accounting')->name('accounting.')->group(function () {
        Route::get('/', [AccountingController::class, 'index'])->name('index');
    Route::get('/search', [AccountingController::class, 'search'])->name('search');
    Route::post('/update', [AccountingController::class, 'updateOverdueInvoices'])->name('update');

        // DÃƒÅ“ZELTME: Rota, Controller'daki doÃ„Å¸ru metod adÃ„Â± olan 'create' metodunu iÃ…Å¸aret ediyor.
        Route::get('/new', [AccountingController::class, 'create'])->name('new');

        Route::match(['get', 'post'], '/prepare', [AccountingController::class, 'prepare'])->name('prepare');
        Route::post('/', [AccountingController::class, 'store'])->name('store');

        Route::get('/invoices/{invoice}/show', [AccountingController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/payment', [AccountingController::class, 'payment'])->name('invoices.payment');
        Route::post('/invoices/{invoice}/payments', [AccountingController::class, 'storePayment'])->name('invoices.store-payment');
        Route::delete('/invoices/{invoice}/payments/{payment}', [AccountingController::class, 'removePayment'])->name('invoices.remove-payment');
        Route::get('/invoices/{invoice}/action', [AccountingController::class, 'action'])->name('invoices.action');
        Route::post('/invoices/{invoice}/items', [AccountingController::class, 'addItem'])->name('invoices.items.store');
        Route::put('/invoices/{invoice}/items/{item}', [AccountingController::class, 'updateItem'])->name('invoices.items.update');
        Route::delete('/invoices/{invoice}/items/{item}', [AccountingController::class, 'destroyItem'])->name('invoices.items.destroy');
        Route::patch('/invoices/{invoice}/status', [AccountingController::class, 'updateStatus'])->name('invoices.update-status');
        Route::put('/invoices/{invoice}', [AccountingController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{invoice}', [AccountingController::class, 'destroy'])->name('invoices.destroy');
        Route::get('/trash', [AccountingController::class, 'trash'])->name('trash');
        Route::post('/trash/{invoiceId}/restore', [AccountingController::class, 'restore'])->name('trash.restore');
        Route::post('/trash/bulk-restore', [AccountingController::class, 'bulkRestore'])->name('trash.bulk-restore');
        Route::post('/trash/bulk-force-delete', [AccountingController::class, 'bulkForceDelete'])->name('trash.bulk-force-delete');
        Route::post('/trash/{invoiceId}/remove', [AccountingController::class, 'forceDelete'])->name('trash.remove');
    });

    // Bekleme OdasÃ„Â± RotalarÃ„Â±
    Route::prefix('waiting-room')->name('waiting-room.')->group(function () {
        Route::get('/', [WaitingRoomController::class, 'index'])->name('index');
        Route::get('/appointments', [WaitingRoomController::class, 'appointments'])->name('appointments');
        Route::get('/appointments/create', [WaitingRoomController::class, 'createAppointment'])->name('appointments.create');
        Route::post('/appointments', [WaitingRoomController::class, 'storeAppointment'])->name('appointments.store');
        Route::get('/appointments/search', [WaitingRoomController::class, 'searchAppointments'])->name('appointments.search');
        Route::get('/patient-treatment-plan-items', [WaitingRoomController::class, 'getPatientTreatmentPlanItems'])->name('patient-treatment-plan-items');
        Route::get('/emergency', [WaitingRoomController::class, 'emergency'])->name('emergency');
        Route::get('/emergency/add', [WaitingRoomController::class, 'createEmergency'])->name('emergency.create');
        Route::post('/emergency', [WaitingRoomController::class, 'storeEmergency'])->name('emergency.store');
        Route::get('/completed', [WaitingRoomController::class, 'completed'])->name('completed');
        Route::get('/{encounter}/show', [WaitingRoomController::class, 'show'])->name('show');
        Route::get('/{encounter}/action', [WaitingRoomController::class, 'action'])->name('action');
        Route::match(['post', 'put'], '/{encounter}/action', [WaitingRoomController::class, 'updateAction'])->name('action.update');
    });

    // KVKK RotalarÄ±
    Route::prefix('kvkk')->name('kvkk.')->group(function () {
        Route::get('/', [\App\Http\Controllers\KvkkController::class, 'index'])->name('index');
        Route::get('/search', [\App\Http\Controllers\KvkkController::class, 'search'])->name('search');
        Route::get('/trash', [\App\Http\Controllers\KvkkController::class, 'restoreIndex'])->name('trash.index')->middleware('can:accessAdminFeatures');
        Route::get('/{patient}', [\App\Http\Controllers\KvkkController::class, 'show'])->name('show');
        Route::match(['get', 'post'], '/{patient}/export', [\App\Http\Controllers\KvkkController::class, 'export'])->name('export');
        Route::get('/download-export/{filename}', [\App\Http\Controllers\KvkkController::class, 'downloadExport'])->name('download-export');
        Route::post('/{patient}/soft-delete', [\App\Http\Controllers\KvkkController::class, 'softDelete'])->name('soft-delete');
        Route::get('/{patient}/hard-delete/confirm', [\App\Http\Controllers\KvkkController::class, 'hardDeleteConfirm'])->name('hard-delete.confirm')->middleware('can:accessAdminFeatures');
        Route::post('/hard-delete/{patientId}', [\App\Http\Controllers\KvkkController::class, 'hardDelete'])->name('hard-delete')->middleware('can:accessAdminFeatures');
        Route::post('/restore/{patientId}', [\App\Http\Controllers\KvkkController::class, 'restore'])->name('restore')->middleware('can:accessAdminFeatures');

        // Consent Management
        Route::get('/{patient}/create-consent', [\App\Http\Controllers\KvkkController::class, 'createConsent'])->name('create-consent');
        Route::post('/{patient}/create-consent', [\App\Http\Controllers\KvkkController::class, 'storeConsent'])->name('store-consent');
        Route::get('/{patient}/consent-success', [\App\Http\Controllers\KvkkController::class, 'consentSuccess'])->name('consent-success');
        Route::get('/{patient}/consent-pdf', [\App\Http\Controllers\KvkkController::class, 'downloadConsentPdf'])->name('consent-pdf');

        // Email Verification
        Route::get('/verify-consent/{token}', [\App\Http\Controllers\KvkkController::class, 'showVerifyConsent'])->name('verify-consent');
        Route::post('/verify-consent/{token}', [\App\Http\Controllers\KvkkController::class, 'processVerifyConsent'])->name('process-verify-consent');

        // Consent Cancellation
        Route::get('/{patient}/cancel-consent', [\App\Http\Controllers\KvkkController::class, 'cancelConsent'])->name('cancel-consent');
        Route::get('/{patient}/cancel-consent/pdf', [\App\Http\Controllers\KvkkController::class, 'downloadCancellationPdf'])->name('cancel-consent.pdf');
        Route::post('/{patient}/cancel-consent', [\App\Http\Controllers\KvkkController::class, 'processCancelConsent'])->name('process-cancel-consent');

        // Reports
        Route::get('/reports/missing', [\App\Http\Controllers\KvkkReportsController::class, 'missingConsents'])->name('reports.missing');
    });
});

// Development/Test Routes (Remove in production)
if (app()->environment(['local', 'development'])) {
    Route::get('/test-error-403', function () {
        abort(403, 'Test 403 Error - Yetkisiz Erişim');
    });

    Route::get('/test-error-404', function () {
        abort(404, 'Test 404 Error - Sayfa Bulunamadı');
    });

    Route::get('/test-error-logging', function () {
        // Test error logging service
        \App\Services\ErrorLoggerService::logErrorPage(request(), 500, 'Test Error Logging');
        return response()->json(['message' => 'Error logged successfully']);
    });
}

require __DIR__.'/auth.php';








