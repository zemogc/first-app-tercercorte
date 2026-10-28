<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\Geografia\MunicipioRequest;
use App\Http\Resources\MunicipioResource;
use App\Models\Municipio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MunicipioController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $municipios = Municipio::with('departamento')
            ->withCount('comunas')
            ->buscar($request->query('q'))
            ->when($request->query('departamento'), fn ($q, $depa) => $q->where('depa_codi', $depa))
            ->when($request->query('pais'), fn ($q, $pais) => $q->whereHas(
                'departamento', fn ($d) => $d->where('pais_codi', strtoupper($pais))
            ))
            ->when(
                $request->query('orden') === 'poblacion',
                fn ($q) => $q->orderByDesc('muni_pobl'),
                fn ($q) => $q->orderBy('muni_nomb'),
            )
            ->paginate($this->porPagina($request))
            ->withQueryString();

        return MunicipioResource::collection($municipios);
    }

    public function store(MunicipioRequest $request): JsonResponse
    {
        $municipio = Municipio::create($request->validated());

        return (new MunicipioResource($municipio->load('departamento')))->response()->setStatusCode(201);
    }

    public function show(Municipio $municipio): MunicipioResource
    {
        return new MunicipioResource($municipio
            ->load(['departamento.pais', 'comunas' => fn ($q) => $q->orderBy('comu_codi')])
            ->loadCount('comunas'));
    }

    public function update(MunicipioRequest $request, Municipio $municipio): MunicipioResource
    {
        $municipio->update($request->validated());

        return new MunicipioResource($municipio->load('departamento'));
    }

    public function destroy(Municipio $municipio): JsonResponse
    {
        return $this->eliminar($municipio);
    }
}
