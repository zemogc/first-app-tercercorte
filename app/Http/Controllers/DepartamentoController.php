<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\EliminaConSeguridad;
use App\Http\Requests\Geografia\DepartamentoRequest;
use App\Models\Departamento;
use App\Models\Pais;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartamentoController extends Controller
{
    use EliminaConSeguridad;

    public function index(Request $request): View
    {
        $departamentos = Departamento::with('pais')
            ->withCount('municipios')
            ->buscar($request->query('q'))
            ->orderBy('depa_nomb')
            ->paginate(10)
            ->withQueryString();

        return view('departamento.index', ['departamentos' => $departamentos]);
    }

    public function create(): View
    {
        return view('departamento.new', ['paises' => Pais::orderBy('pais_nomb')->get()]);
    }

    public function store(DepartamentoRequest $request): RedirectResponse
    {
        $departamento = Departamento::create($request->validated());

        return redirect()->route('departamentos.index')
            ->with('success', "Departamento {$departamento->depa_nomb} creado correctamente.");
    }

    public function show(Departamento $departamento): View
    {
        $departamento->load(['pais', 'municipios' => fn ($q) => $q->withCount('comunas')->orderByDesc('muni_pobl')]);

        return view('departamento.show', ['departamento' => $departamento]);
    }

    public function edit(Departamento $departamento): View
    {
        return view('departamento.edit', [
            'departamento' => $departamento,
            'paises' => Pais::orderBy('pais_nomb')->get(),
        ]);
    }

    public function update(DepartamentoRequest $request, Departamento $departamento): RedirectResponse
    {
        $departamento->update($request->validated());

        return redirect()->route('departamentos.index')
            ->with('success', "Departamento {$departamento->depa_nomb} actualizado.");
    }

    public function destroy(Departamento $departamento): RedirectResponse
    {
        return $this->eliminar($departamento, 'departamentos.index', 'el departamento');
    }
}
