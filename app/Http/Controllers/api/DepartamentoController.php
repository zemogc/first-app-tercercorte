<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\Geografia\DepartamentoRequest;
use App\Http\Resources\DepartamentoResource;
use App\Models\Departamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DepartamentoController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $departamentos = Departamento::with('pais')
            ->withCount('municipios')
            ->withSum('municipios', 'muni_pobl')
            ->buscar($request->query('q'))
            ->when($request->query('pais'), fn ($q, $pais) => $q->where('pais_codi', strtoupper($pais)))
            ->orderBy('depa_nomb')
            ->paginate($this->porPagina($request))
            ->withQueryString();

        return DepartamentoResource::collection($departamentos);
    }

    public function store(DepartamentoRequest $request): JsonResponse
    {
        $departamento = Departamento::create($request->validated());

        return (new DepartamentoResource($departamento->load('pais')))->response()->setStatusCode(201);
    }

    public function show(Departamento $departamento): DepartamentoResource
    {
        return new DepartamentoResource($departamento
            ->load(['pais', 'municipios' => fn ($q) => $q->withCount('comunas')->orderByDesc('muni_pobl')])
            ->loadCount('municipios')
            ->loadSum('municipios', 'muni_pobl'));
    }

    public function update(DepartamentoRequest $request, Departamento $departamento): DepartamentoResource
    {
        $departamento->update($request->validated());

        return new DepartamentoResource($departamento->load('pais'));
    }

    public function destroy(Departamento $departamento): JsonResponse
    {
        return $this->eliminar($departamento);
    }
}
