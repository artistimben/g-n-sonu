<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\SetupController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/giris', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/giris', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/kurulum', [SetupController::class, 'create'])->name('setup');
    Route::post('/kurulum', [SetupController::class, 'store'])->name('setup.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/cikis-yap', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::post('/mesai/giris', [AttendanceController::class, 'clockIn'])->name('attendance.in');
    Route::post('/mesai/cikis', [AttendanceController::class, 'clockOut'])->name('attendance.out');

    Route::get('/raporlar', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/raporlar/yeni', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/raporlar', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/raporlar/{report}/duzenle', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/raporlar/{report}', [ReportController::class, 'update'])->name('reports.update');

    Route::middleware('can:manage-team')->group(function () {
        Route::get('/ekip', [EmployeeController::class, 'index'])->name('employees.index');
        Route::post('/ekip', [EmployeeController::class, 'store'])->name('employees.store');
    });
});
