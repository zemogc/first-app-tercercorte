<?php

namespace App\Http\Controllers;

use App\Models\Comuna;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'totales' => [
                'paises' => Pais::count(),
                'departamentos' => Departamento::count(),
                'municipios' => Municipio::count(),
                'comunas' => Comuna::count(),
            ],
            'poblacion' => (int) Municipio::sum('muni_pobl'),
            'topMunicipios' => Municipio::with('departamento.pais')
                ->whereNotNull('muni_pobl')
                ->orderByDesc('muni_pobl')
                ->limit(8)
                ->get(),
            'departamentosPorPais' => Pais::withCount('departamentos')
                ->orderByDesc('departamentos_count')
                ->get(['pais_codi', 'pais_nomb']),
        ]);
    }
}
