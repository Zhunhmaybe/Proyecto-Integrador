<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auditor\AuditorDashboardController;
use App\Http\Controllers\Auditor\AuditLogController;
use App\Http\Controllers\Auditor\TableViewController;

/*
|--------------------------------------------------------------------------
| Rutas del Auditor
|--------------------------------------------------------------------------
|
| Estas rutas están protegidas por el middleware 'auth' y 'auditor'
| Solo los usuarios con rol 'auditor' pueden acceder a estas rutas
|
*/

Route::middleware(['auth', 'auditor'])->prefix('auditor')->name('auditor.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AuditorDashboardController::class, 'index'])->name('dashboard');

    // Logs de Auditoría
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
        Route::get('/{id}', [AuditLogController::class, 'show'])->name('show');
        Route::get('/export/csv', [AuditLogController::class, 'export'])->name('export');
    });

    // Visualización de Tablas
    Route::prefix('tables')->name('tables.')->group(function () {
        Route::get('/users', [TableViewController::class, 'users'])->name('users');
        Route::get('/citas', [TableViewController::class, 'citas'])->name('citas');
        Route::get('/pacientes', [TableViewController::class, 'pacientes'])->name('pacientes');
        Route::get('/custom-query', [TableViewController::class, 'customQuery'])->name('custom_query');
    });
});
