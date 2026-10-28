<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\Geografia\ComunaRequest;
use App\Http\Resources\ComunaResource;
use App\Models\Comuna;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ComunaController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $comunas = Comuna::with('municipio')
            ->buscar($request->query('q'))
            ->when($request->query('municipio'), fn ($q, $muni) => $q->where('muni_codi', $muni))
            ->orderBy('muni_codi')
            ->orderBy('comu_codi')
            ->paginate($this->porPagina($request))
            ->withQueryString();

        return ComunaResource::collection($comunas);
    }

    public function store(ComunaRequest $request): JsonResponse
    {
        $comuna = Comuna::create($request->validated());

        return (new ComunaResource($comuna->load('municipio')))->response()->setStatusCode(201);
    }

    public function show(Comuna $comuna): ComunaResource
    {
        return new ComunaResource($comuna->load('municipio.departamento'));
    }

    public function update(ComunaRequest $request, Comuna $comuna): ComunaResource
    {
        $comuna->update($request->validated());

        return new ComunaResource($comuna->load('municipio'));
    }

    public function destroy(Comuna $comuna): JsonResponse
    {
        return $this->eliminar($comuna);
    }
}
