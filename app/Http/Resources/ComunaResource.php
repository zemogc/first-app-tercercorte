<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Comuna */
class ComunaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codigo' => $this->comu_codi,
            'nombre' => $this->comu_nomb,
            'municipio_codigo' => $this->muni_codi,
            'municipio' => new MunicipioResource($this->whenLoaded('municipio')),
        ];
    }
}
