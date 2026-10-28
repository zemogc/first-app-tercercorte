<?php

use App\Http\Controllers\api\ComunaController;
use App\Http\Controllers\api\DepartamentoController;
use App\Http\Controllers\api\EstadisticasController;
use App\Http\Controllers\api\MunicipioController;
use App\Http\Controllers\api\PaisController;
use App\Http\Controllers\api\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$recursos = [
    'paises' => PaisController::class,
    'departamentos' => DepartamentoController::class,
    'municipios' => MunicipioController::class,
    'comunas' => ComunaController::class,
];

// Lectura pública
Route::name('api.')->middleware('throttle:api')->group(function () use ($recursos) {
    Route::get('/estadisticas', EstadisticasController::class)->name('estadisticas');
    Route::post('/tokens', [TokenController::class, 'store'])->middleware('throttle:6,1')->name('tokens.store');

    foreach ($recursos as $nombre => $controlador) {
        Route::apiResource($nombre, $controlador)->only(['index', 'show'])->parameters(['paises' => 'pais']);
    }
});

// Escritura protegida con token Sanctum
Route::name('api.')->middleware(['auth:sanctum', 'throttle:api'])->group(function () use ($recursos) {
    Route::get('/user', fn (Request $request) => $request->user())->name('user');
    Route::delete('/tokens/actual', [TokenController::class, 'destroy'])->name('tokens.destroy');

    foreach ($recursos as $nombre => $controlador) {
        Route::apiResource($nombre, $controlador)->except(['index', 'show'])->parameters(['paises' => 'pais']);
    }
});
