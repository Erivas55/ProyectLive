<?php

use App\Http\Controllers\CompanieController;
use App\Http\Controllers\EmploymentController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PostulationController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('auth')->group(function () {

    // Mi perfil: cualquier usuario autenticado (el controlador valida que sea el suyo).
    Route::resource('perfil', PerfilController::class)->except(['index', 'destroy']);

    // Solo Administrador: Roles y Empresas.
    Route::middleware('role:administrador')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);

        Route::resource('empresas', CompanieController::class)
            ->except(['show'])
            ->parameters(['empresas' => 'companie']);
    });

    // Administración de Vacantes: Administrador y Reclutador.
    // (Debe registrarse antes que show para que /vacantes/create no se interprete como un ID.)
    Route::middleware('role:administrador,reclutador')->group(function () {
        Route::resource('vacantes', EmploymentController::class)
            ->except(['index', 'show'])
            ->parameters(['vacantes' => 'employment']);
    });

    // Consulta de Vacantes: los tres roles (la política filtra qué ve cada uno).
    Route::middleware('role:administrador,reclutador,candidato')->group(function () {
        Route::resource('vacantes', EmploymentController::class)
            ->only(['index', 'show'])
            ->parameters(['vacantes' => 'employment']);

        Route::resource('postulaciones', PostulationController::class)
            ->only(['index', 'show', 'destroy'])
            ->parameters(['postulaciones' => 'postulation']);
    });

    // Postularse: solo Candidato.
    Route::post('vacantes/{employment}/postular', [PostulationController::class, 'store'])
        ->middleware('role:candidato')
        ->name('postulaciones.store');
});

require __DIR__.'/settings.php';
