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
use App\Http\Controllers\VisiteController;
use App\Http\Controllers\PavillonController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RapportController;

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

        // Visites
        Route::get('/visites', [VisiteController::class, 'index'])->name('visites.index');
        Route::get('/visites/create', [VisiteController::class, 'create'])->name('visites.create');
        Route::post('/visites', [VisiteController::class, 'store'])->name('visites.store');
        Route::put('/visites/{visite}/checkout', [VisiteController::class, 'checkout'])->name('visites.checkout');

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
        Route::get('/demandes/create', [DemandeController::class, 'create'])->name('demandes.create');
        Route::post('/demandes', [DemandeController::class, 'store'])->name('demandes.store');
        Route::get('/demandes/{demande}', [DemandeController::class, 'show'])->name('demandes.show');
        Route::put('/demandes/{demande}/status', [DemandeController::class, 'updateStatus'])->name('demandes.updateStatus');

        // Sanctions
        Route::get('/sanctions', [SanctionController::class, 'index'])->name('sanctions.index');
        Route::get('/sanctions/create', [SanctionController::class, 'create'])->name('sanctions.create');
        Route::post('/sanctions', [SanctionController::class, 'store'])->name('sanctions.store');
        Route::get('/sanctions/{sanction}', [SanctionController::class, 'show'])->name('sanctions.show');
        Route::put('/sanctions/{sanction}/status', [SanctionController::class, 'updateStatus'])->name('sanctions.updateStatus');
    });

    Route::middleware('role:admin')->group(function () {
        // Étudiants - traitement académique
        Route::post('/students/process-academic-year', [StudentController::class, 'processAcademicYear'])->name('students.processAcademicYear');

        // Pavillons
        Route::get('/pavillons', [PavillonController::class, 'index'])->name('pavillons.index');
        Route::get('/pavillons/create', [PavillonController::class, 'create'])->name('pavillons.create');
        Route::post('/pavillons', [PavillonController::class, 'store'])->name('pavillons.store');
        Route::get('/pavillons/{pavillon}/edit', [PavillonController::class, 'edit'])->name('pavillons.edit');
        Route::put('/pavillons/{pavillon}', [PavillonController::class, 'update'])->name('pavillons.update');
        Route::delete('/pavillons/{pavillon}', [PavillonController::class, 'destroy'])->name('pavillons.destroy');

        // Chambres
        Route::get('/chambres', [ChambreController::class, 'index'])->name('chambres.index');
        Route::get('/chambres/create', [ChambreController::class, 'create'])->name('chambres.create');
        Route::post('/chambres', [ChambreController::class, 'store'])->name('chambres.store');
        Route::get('/chambres/{chambre}/edit', [ChambreController::class, 'edit'])->name('chambres.edit');
        Route::put('/chambres/{chambre}', [ChambreController::class, 'update'])->name('chambres.update');
        Route::delete('/chambres/{chambre}', [ChambreController::class, 'destroy'])->name('chambres.destroy');

        // Utilisateurs
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Rapports
        Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
        Route::get('/rapports/export/etudiants', [RapportController::class, 'exportEtudiants'])->name('rapports.export.etudiants');
        Route::get('/rapports/export/sanctions', [RapportController::class, 'exportSanctions'])->name('rapports.export.sanctions');
        Route::get('/rapports/export/demandes', [RapportController::class, 'exportDemandes'])->name('rapports.export.demandes');
        Route::get('/rapports/export/visites', [RapportController::class, 'exportVisites'])->name('rapports.export.visites');
        Route::get('/rapports/export/occupation', [RapportController::class, 'exportOccupation'])->name('rapports.export.occupation');
    });
});
