<?php

use App\Http\Controllers\AsistenteController;
use App\Http\Controllers\ComunaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\ProfileController;
use App\Models\Comuna;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'totales' => [
            'Países' => Pais::count(),
            'Departamentos' => Departamento::count(),
            'Municipios' => Municipio::count(),
            'Comunas' => Comuna::count(),
        ],
    ]);
})->name('home');

Route::view('/documentacion-api', 'api-docs')->name('api.docs');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('paises', PaisController::class)->parameters(['paises' => 'pais']);
    Route::resource('departamentos', DepartamentoController::class);
    Route::resource('municipios', MunicipioController::class);
    Route::resource('comunas', ComunaController::class)->except('show');

    Route::get('/asistente', [AsistenteController::class, 'index'])->name('asistente');
    Route::post('/asistente', [AsistenteController::class, 'preguntar'])
        ->middleware('throttle:asistente')
        ->name('asistente.preguntar');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
