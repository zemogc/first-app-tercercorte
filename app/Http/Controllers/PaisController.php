<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\EliminaConSeguridad;
use App\Http\Requests\Geografia\PaisRequest;
use App\Models\Municipio;
use App\Models\Pais;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaisController extends Controller
{
    use EliminaConSeguridad;

    public function index(Request $request): View
    {
        $paises = Pais::with('capital')
            ->withCount('departamentos')
            ->buscar($request->query('q'))
            ->orderBy('pais_nomb')
            ->paginate(10)
            ->withQueryString();

        return view('pais.index', ['paises' => $paises]);
    }

    public function create(): View
    {
        return view('pais.new', ['municipios' => Municipio::orderBy('muni_nomb')->get()]);
    }

    public function store(PaisRequest $request): RedirectResponse
    {
        $pais = Pais::create($request->validated());

        return redirect()->route('paises.index')->with('success', "País {$pais->pais_nomb} creado correctamente.");
    }

    public function show(Pais $pais): View
    {
        $pais->load(['capital', 'departamentos' => fn ($q) => $q->withCount('municipios')->orderBy('depa_nomb')]);

        return view('pais.show', [
            'pais' => $pais,
            'poblacion' => (int) $pais->municipios()->sum('muni_pobl'),
        ]);
    }

    public function edit(Pais $pais): View
    {
        return view('pais.edit', [
            'pais' => $pais,
            'municipios' => Municipio::orderBy('muni_nomb')->get(),
        ]);
    }

    public function update(PaisRequest $request, Pais $pais): RedirectResponse
    {
        $pais->update($request->validated());

        return redirect()->route('paises.index')->with('success', "País {$pais->pais_nomb} actualizado.");
    }

    public function destroy(Pais $pais): RedirectResponse
    {
        return $this->eliminar($pais, 'paises.index', 'el país');
    }
}
