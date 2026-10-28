<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Municipio */
class MunicipioResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codigo' => $this->muni_codi,
            'nombre' => $this->muni_nomb,
            'poblacion' => $this->muni_pobl,
            'departamento_codigo' => $this->depa_codi,
            'departamento' => new DepartamentoResource($this->whenLoaded('departamento')),
            'total_comunas' => $this->whenCounted('comunas'),
            'comunas' => ComunaResource::collection($this->whenLoaded('comunas')),
        ];
    }
}
