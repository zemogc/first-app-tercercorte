<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Departamento */
class DepartamentoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codigo' => $this->depa_codi,
            'nombre' => $this->depa_nomb,
            'pais_codigo' => $this->pais_codi,
            'pais' => new PaisResource($this->whenLoaded('pais')),
            'total_municipios' => $this->whenCounted('municipios'),
            'poblacion' => $this->whenAggregated('municipios', 'muni_pobl', 'sum', fn ($v) => (int) $v),
            'municipios' => MunicipioResource::collection($this->whenLoaded('municipios')),
        ];
    }
}
