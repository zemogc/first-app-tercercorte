<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Pais */
class PaisResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codigo' => $this->pais_codi,
            'nombre' => $this->pais_nomb,
            'capital' => $this->whenLoaded('capital', fn () => $this->capital ? [
                'codigo' => $this->capital->muni_codi,
                'nombre' => $this->capital->muni_nomb,
            ] : null),
            'total_departamentos' => $this->whenCounted('departamentos'),
            'departamentos' => DepartamentoResource::collection($this->whenLoaded('departamentos')),
        ];
    }
}
