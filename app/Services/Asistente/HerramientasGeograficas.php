<?php

namespace App\Services\Asistente;

use App\Models\Comuna;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;
use Illuminate\Database\Eloquent\Builder;

/**
 * Herramientas que el asistente puede invocar para consultar la base de datos.
 * Cada herramienta recibe la entrada ya decodificada y devuelve un arreglo
 * serializable a JSON.
 */
class HerramientasGeograficas
{
    /**
     * Definiciones en el formato que espera la API de mensajes.
     *
     * @return list<array<string, mixed>>
     */
    public function definiciones(): array
    {
        return [
            [
                'name' => 'buscar_lugares',
                'description' => 'Busca países, departamentos, municipios y comunas cuyo nombre contenga el texto dado. Úsala primero cuando no sepas a qué nivel pertenece un lugar.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'texto' => ['type' => 'string', 'description' => 'Parte del nombre a buscar, por ejemplo "valle" o "Cali".'],
                    ],
                    'required' => ['texto'],
                    'additionalProperties' => false,
                ],
            ],
            [
                'name' => 'detalle_pais',
                'description' => 'Devuelve un país con su capital, población registrada y la lista de sus departamentos con número de municipios.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'pais' => ['type' => 'string', 'description' => 'Código ISO de 3 letras (COL) o nombre del país.'],
                    ],
                    'required' => ['pais'],
                    'additionalProperties' => false,
                ],
            ],
            [
                'name' => 'detalle_departamento',
                'description' => 'Devuelve un departamento con su país y todos sus municipios con población y número de comunas.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'nombre' => ['type' => 'string', 'description' => 'Nombre del departamento.'],
                    ],
                    'required' => ['nombre'],
                    'additionalProperties' => false,
                ],
            ],
            [
                'name' => 'detalle_municipio',
                'description' => 'Devuelve un municipio con su departamento, país, población y la lista de sus comunas o localidades.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'nombre' => ['type' => 'string', 'description' => 'Nombre del municipio.'],
                    ],
                    'required' => ['nombre'],
                    'additionalProperties' => false,
                ],
            ],
            [
                'name' => 'ranking_poblacion',
                'description' => 'Ranking de municipios o departamentos por población registrada, opcionalmente filtrado por país.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'nivel' => ['type' => 'string', 'enum' => ['municipios', 'departamentos']],
                        'pais' => ['type' => 'string', 'description' => 'Código ISO de 3 letras para filtrar. Opcional.'],
                        'limite' => ['type' => 'integer', 'description' => 'Cantidad de resultados, entre 1 y 20.'],
                    ],
                    'required' => ['nivel'],
                    'additionalProperties' => false,
                ],
            ],
            [
                'name' => 'resumen_general',
                'description' => 'Totales de países, departamentos, municipios y comunas registrados y población total.',
                'inputSchema' => ['type' => 'object', 'properties' => new \stdClass, 'additionalProperties' => false],
            ],
        ];
    }

    /**
     * Ejecuta la herramienta indicada. Lanza InvalidArgumentException si la
     * herramienta no existe o la entrada no es válida.
     *
     * @param  array<string, mixed>  $entrada
     * @return array<string, mixed>
     */
    public function ejecutar(string $nombre, array $entrada): array
    {
        return match ($nombre) {
            'buscar_lugares' => $this->buscarLugares($this->texto($entrada, 'texto')),
            'detalle_pais' => $this->detallePais($this->texto($entrada, 'pais')),
            'detalle_departamento' => $this->detalleDepartamento($this->texto($entrada, 'nombre')),
            'detalle_municipio' => $this->detalleMunicipio($this->texto($entrada, 'nombre')),
            'ranking_poblacion' => $this->rankingPoblacion(
                $entrada['nivel'] ?? 'municipios',
                isset($entrada['pais']) ? (string) $entrada['pais'] : null,
                (int) ($entrada['limite'] ?? 10),
            ),
            'resumen_general' => $this->resumenGeneral(),
            default => throw new \InvalidArgumentException("Herramienta desconocida: {$nombre}"),
        };
    }

    public function buscarLugares(string $texto): array
    {
        $like = fn (Builder $q, string $columna) => $q->where($columna, 'like', "%{$texto}%")->limit(10);

        return [
            'paises' => $like(Pais::query(), 'pais_nomb')->get()
                ->map(fn ($p) => ['codigo' => $p->pais_codi, 'nombre' => $p->pais_nomb])->all(),
            'departamentos' => $like(Departamento::with('pais'), 'depa_nomb')->get()
                ->map(fn ($d) => ['nombre' => $d->depa_nomb, 'pais' => $d->pais?->pais_nomb])->all(),
            'municipios' => $like(Municipio::with('departamento'), 'muni_nomb')->get()
                ->map(fn ($m) => ['nombre' => $m->muni_nomb, 'departamento' => $m->departamento?->depa_nomb, 'poblacion' => $m->muni_pobl])->all(),
            'comunas' => $like(Comuna::with('municipio'), 'comu_nomb')->get()
                ->map(fn ($c) => ['nombre' => $c->comu_nomb, 'municipio' => $c->municipio?->muni_nomb])->all(),
        ];
    }

    public function detallePais(string $pais): array
    {
        $registro = Pais::with(['capital', 'departamentos' => fn ($q) => $q->withCount('municipios')->orderBy('depa_nomb')])
            ->where('pais_codi', strtoupper($pais))
            ->orWhere('pais_nomb', 'like', $pais)
            ->first();

        if (! $registro) {
            return ['error' => "No hay ningún país registrado como «{$pais}»."];
        }

        return [
            'codigo' => $registro->pais_codi,
            'nombre' => $registro->pais_nomb,
            'capital' => $registro->capital?->muni_nomb,
            'poblacion_registrada' => (int) $registro->municipios()->sum('muni_pobl'),
            'departamentos' => $registro->departamentos
                ->map(fn ($d) => ['nombre' => $d->depa_nomb, 'municipios' => $d->municipios_count])->all(),
        ];
    }

    public function detalleDepartamento(string $nombre): array
    {
        $registro = $this->porNombre(Departamento::query(), 'depa_nomb', $nombre)
            ?->load(['pais', 'municipios' => fn ($q) => $q->withCount('comunas')->orderByDesc('muni_pobl')]);

        if (! $registro) {
            return ['error' => "No hay ningún departamento registrado como «{$nombre}»."];
        }

        return [
            'nombre' => $registro->depa_nomb,
            'pais' => $registro->pais?->pais_nomb,
            'poblacion_registrada' => (int) $registro->municipios->sum('muni_pobl'),
            'municipios' => $registro->municipios
                ->map(fn ($m) => ['nombre' => $m->muni_nomb, 'poblacion' => $m->muni_pobl, 'comunas' => $m->comunas_count])->all(),
        ];
    }

    public function detalleMunicipio(string $nombre): array
    {
        $registro = $this->porNombre(Municipio::query(), 'muni_nomb', $nombre)
            ?->load(['departamento.pais', 'comunas' => fn ($q) => $q->orderBy('comu_codi')]);

        if (! $registro) {
            return ['error' => "No hay ningún municipio registrado como «{$nombre}»."];
        }

        return [
            'nombre' => $registro->muni_nomb,
            'departamento' => $registro->departamento?->depa_nomb,
            'pais' => $registro->departamento?->pais?->pais_nomb,
            'poblacion' => $registro->muni_pobl,
            'es_capital_del_pais' => Pais::where('pais_capi', $registro->muni_codi)->exists(),
            'comunas' => $registro->comunas->pluck('comu_nomb')->all(),
        ];
    }

    public function rankingPoblacion(string $nivel, ?string $pais, int $limite): array
    {
        $limite = max(1, min(20, $limite));
        $pais = $pais ? strtoupper($pais) : null;

        if ($nivel === 'departamentos') {
            return ['departamentos' => Departamento::with('pais')
                ->withSum('municipios', 'muni_pobl')
                ->when($pais, fn ($q) => $q->where('pais_codi', $pais))
                ->orderByDesc('municipios_sum_muni_pobl')
                ->limit($limite)
                ->get()
                ->map(fn ($d) => ['nombre' => $d->depa_nomb, 'pais' => $d->pais?->pais_nomb, 'poblacion' => (int) $d->municipios_sum_muni_pobl])
                ->all()];
        }

        return ['municipios' => Municipio::with('departamento.pais')
            ->whereNotNull('muni_pobl')
            ->when($pais, fn ($q) => $q->whereHas('departamento', fn ($d) => $d->where('pais_codi', $pais)))
            ->orderByDesc('muni_pobl')
            ->limit($limite)
            ->get()
            ->map(fn ($m) => [
                'nombre' => $m->muni_nomb,
                'departamento' => $m->departamento?->depa_nomb,
                'pais' => $m->departamento?->pais?->pais_nomb,
                'poblacion' => $m->muni_pobl,
            ])
            ->all()];
    }

    public function resumenGeneral(): array
    {
        return [
            'paises' => Pais::count(),
            'departamentos' => Departamento::count(),
            'municipios' => Municipio::count(),
            'comunas' => Comuna::count(),
            'poblacion_registrada' => (int) Municipio::sum('muni_pobl'),
        ];
    }

    /**
     * Busca primero por nombre exacto y luego por coincidencia parcial.
     */
    private function porNombre(Builder $query, string $columna, string $nombre): mixed
    {
        return (clone $query)->where($columna, $nombre)->first()
            ?? $query->where($columna, 'like', "%{$nombre}%")->orderByRaw("length({$columna})")->first();
    }

    private function texto(array $entrada, string $clave): string
    {
        $valor = trim((string) ($entrada[$clave] ?? ''));

        if ($valor === '') {
            throw new \InvalidArgumentException("Falta el parámetro «{$clave}».");
        }

        return $valor;
    }
}
