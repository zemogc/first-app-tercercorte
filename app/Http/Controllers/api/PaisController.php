<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\Geografia\PaisRequest;
use App\Http\Resources\PaisResource;
use App\Models\Pais;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaisController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $paises = Pais::with('capital')
            ->withCount('departamentos')
            ->buscar($request->query('q'))
            ->orderBy('pais_nomb')
            ->paginate($this->porPagina($request))
            ->withQueryString();

        return PaisResource::collection($paises);
    }

    public function store(PaisRequest $request): JsonResponse
    {
        $pais = Pais::create($request->validated());

        return (new PaisResource($pais->load('capital')))->response()->setStatusCode(201);
    }

    public function show(Pais $pais): PaisResource
    {
        return new PaisResource($pais->load([
            'capital',
            'departamentos' => fn ($q) => $q->withCount('municipios')->orderBy('depa_nomb'),
        ])->loadCount('departamentos'));
    }

    public function update(PaisRequest $request, Pais $pais): PaisResource
    {
        $pais->update($request->validated());

        return new PaisResource($pais->load('capital'));
    }

    public function destroy(Pais $pais): JsonResponse
    {
        return $this->eliminar($pais);
    }
}
