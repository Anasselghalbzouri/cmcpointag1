<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SetupController;

Route::get('/setup', [SetupController::class, 'initialize'])->name('setup');
Route::get('/kiosk', [PointageController::class, 'kiosk'])->name('kiosk.index');
Route::post('/kiosk/scan', [PointageController::class, 'kioskScan'])->name('kiosk.scan');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::middleware('role:admin,agent')->group(function () {
        Route::get('/pointage', [PointageController::class, 'index'])->name('pointage.index');
        Route::post('/pointage/scan', [PointageController::class, 'scan'])->name('pointage.scan');
        Route::post('/pointage/manual', [PointageController::class, 'manualEntry'])->name('pointage.manual');
    });
    
    Route::middleware('role:admin,agent')->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    });
});
