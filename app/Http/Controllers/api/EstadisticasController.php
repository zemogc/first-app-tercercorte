<?php

namespace App\Http\Controllers\api;

use App\Http\Resources\MunicipioResource;
use App\Models\Comuna;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;
use Illuminate\Http\JsonResponse;

class EstadisticasController extends ApiController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'totales' => [
                'paises' => Pais::count(),
                'departamentos' => Departamento::count(),
                'municipios' => Municipio::count(),
                'comunas' => Comuna::count(),
            ],
            'poblacion_registrada' => (int) Municipio::sum('muni_pobl'),
            'municipios_mas_poblados' => MunicipioResource::collection(
                Municipio::with('departamento')->whereNotNull('muni_pobl')->orderByDesc('muni_pobl')->limit(5)->get()
            ),
        ]);
    }
}
