<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\SanctionController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\PointageController;

Route::get('/setup', [SetupController::class, 'initialize'])->name('setup');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    Route::middleware('role:admin,security')->group(function () {
        // Pointage (sécurité)
        Route::get('/pointage', [PointageController::class, 'index'])->name('pointage.index');
        Route::post('/pointage/scan', [PointageController::class, 'scan'])->name('pointage.scan');
        Route::post('/pointage/manual', [PointageController::class, 'manualEntry'])->name('pointage.manual');

        // Mouvements
        Route::get('/mouvements', [MouvementController::class, 'index'])->name('mouvements.index');
        Route::get('/mouvements/export', [MouvementController::class, 'export'])->name('mouvements.export');

        // Étudiants CRUD
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

        // Demandes
        Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
        Route::get('/demandes/{demande}', [DemandeController::class, 'show'])->name('demandes.show');
        Route::put('/demandes/{demande}/status', [DemandeController::class, 'updateStatus'])->name('demandes.updateStatus');

        // Sanctions
        Route::get('/sanctions', [SanctionController::class, 'index'])->name('sanctions.index');
        Route::get('/sanctions/{sanction}', [SanctionController::class, 'show'])->name('sanctions.show');
        Route::put('/sanctions/{sanction}/status', [SanctionController::class, 'updateStatus'])->name('sanctions.updateStatus');
    });
});
