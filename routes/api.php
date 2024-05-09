<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ComunaController;
use App\Http\Controllers\api\MunicipioController;
use App\Http\Controllers\api\DepartamentoController;
use App\Http\Controllers\api\PaisController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('comunas', ComunaController::Class);
Route::apiResource('municipios', MunicipioController::Class);
Route::apiResource('departamentos', DepartamentoController::Class);
Route::apiResource('paises', PaisController::Class);