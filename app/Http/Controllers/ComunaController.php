<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\EliminaConSeguridad;
use App\Http\Requests\Geografia\ComunaRequest;
use App\Models\Comuna;
use App\Models\Municipio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComunaController extends Controller
{
    use EliminaConSeguridad;

    public function index(Request $request): View
    {
        $comunas = Comuna::with('municipio.departamento')
            ->buscar($request->query('q'))
            ->orderBy('muni_codi')
            ->orderBy('comu_codi')
            ->paginate(10)
            ->withQueryString();

        return view('comuna.index', ['comunas' => $comunas]);
    }

    public function create(): View
    {
        return view('comuna.new', ['municipios' => Municipio::orderBy('muni_nomb')->get()]);
    }

    public function store(ComunaRequest $request): RedirectResponse
    {
        $comuna = Comuna::create($request->validated());

        return redirect()->route('comunas.index')->with('success', "Comuna {$comuna->comu_nomb} creada correctamente.");
    }

    public function edit(Comuna $comuna): View
    {
        return view('comuna.edit', [
            'comuna' => $comuna,
            'municipios' => Municipio::orderBy('muni_nomb')->get(),
        ]);
    }

    public function update(ComunaRequest $request, Comuna $comuna): RedirectResponse
    {
        $comuna->update($request->validated());

        return redirect()->route('comunas.index')->with('success', "Comuna {$comuna->comu_nomb} actualizada.");
    }

    public function destroy(Comuna $comuna): RedirectResponse
    {
        return $this->eliminar($comuna, 'comunas.index', 'la comuna');
    }
}
