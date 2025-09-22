<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\WaitingRoomController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PdfController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Takvim
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    
    // Bekleme Odası
    Route::get('/waiting-room', [WaitingRoomController::class, 'index'])->name('waiting-room');

    // Raporlar
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // Hasta Yönetimi
    Route::resource('patients', PatientController::class);
    // YENİ: Not güncelleme için özel rota
    Route::patch('/patients/{patient}/notes', [PatientController::class, 'updateNotes'])->name('patients.updateNotes');

    // PDF Rotaları
    Route::get('/invoices/{invoice}/pdf', [PdfController::class, 'downloadInvoice'])->name('invoices.pdf');
    Route::get('/prescriptions/{prescription}/pdf', [PdfController::class, 'downloadPrescription'])->name('prescriptions.pdf');
    
    // Muhasebe Rotaları
    Route::get('/accounting/invoices', [AccountingController::class, 'index'])->name('accounting.invoices.index');
    Route::get('/accounting/invoices/{invoice}', [AccountingController::class, 'show'])->name('accounting.invoices.show');
});

require __DIR__.'/auth.php';

