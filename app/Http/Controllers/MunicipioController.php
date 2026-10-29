<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\EliminaConSeguridad;
use App\Http\Requests\Geografia\MunicipioRequest;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MunicipioController extends Controller
{
    use EliminaConSeguridad;

    public function index(Request $request): View
    {
        $municipios = Municipio::with('departamento.pais')
            ->withCount('comunas')
            ->buscar($request->query('q'))
            ->orderBy('muni_nomb')
            ->paginate(10)
            ->withQueryString();

        return view('municipio.index', ['municipios' => $municipios]);
    }

    public function create(): View
    {
        return view('municipio.new', ['departamentos' => Departamento::with('pais')->orderBy('depa_nomb')->get()]);
    }

    public function store(MunicipioRequest $request): RedirectResponse
    {
        $municipio = Municipio::create($request->validated());

        return redirect()->route('municipios.index')
            ->with('success', "Municipio {$municipio->muni_nomb} creado correctamente.");
    }

    public function show(Municipio $municipio): View
    {
        $municipio->load(['departamento.pais', 'comunas' => fn ($q) => $q->orderBy('comu_codi')]);

        return view('municipio.show', ['municipio' => $municipio]);
    }

    public function edit(Municipio $municipio): View
    {
        return view('municipio.edit', [
            'municipio' => $municipio,
            'departamentos' => Departamento::with('pais')->orderBy('depa_nomb')->get(),
        ]);
    }

    public function update(MunicipioRequest $request, Municipio $municipio): RedirectResponse
    {
        $municipio->update($request->validated());

        return redirect()->route('municipios.index')
            ->with('success', "Municipio {$municipio->muni_nomb} actualizado.");
    }

    public function destroy(Municipio $municipio): RedirectResponse
    {
        return $this->eliminar($municipio, 'municipios.index', 'el municipio');
    }
}
