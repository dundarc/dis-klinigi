<?php

use App\Modules\Installation\Controllers\InstallationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'install', 'middleware' => 'installation'], function () {
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