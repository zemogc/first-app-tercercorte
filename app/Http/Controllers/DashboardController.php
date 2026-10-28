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
        $poblacionPorDepartamento = Departamento::withSum('municipios', 'muni_pobl')
            ->orderByDesc('municipios_sum_muni_pobl')
            ->limit(10)
            ->get();

        $departamentosPorPais = Pais::withCount('departamentos')
            ->orderByDesc('departamentos_count')
            ->get(['pais_codi', 'pais_nomb']);

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
            'graficaPoblacion' => [
                'serie' => 'Población',
                'etiquetas' => $poblacionPorDepartamento->pluck('depa_nomb'),
                'valores' => $poblacionPorDepartamento->pluck('municipios_sum_muni_pobl')->map(fn ($v) => (int) $v),
            ],
            'graficaPaises' => [
                'etiquetas' => $departamentosPorPais->pluck('pais_nomb'),
                'valores' => $departamentosPorPais->pluck('departamentos_count'),
            ],
            'departamentosPorPais' => $departamentosPorPais,
        ]);
    }
}
