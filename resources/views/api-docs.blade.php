@php
$endpoints = [
    ['GET', '/api/paises', 'Lista paginada de países con su capital.', 'q, per_page'],
    ['GET', '/api/paises/{codigo}', 'Detalle de un país con sus departamentos.', null],
    ['GET', '/api/departamentos', 'Departamentos con total de municipios y población.', 'q, pais, per_page'],
    ['GET', '/api/departamentos/{codigo}', 'Detalle con municipios ordenados por población.', null],
    ['GET', '/api/municipios', 'Municipios con su departamento.', 'q, pais, departamento, orden=poblacion, per_page'],
    ['GET', '/api/municipios/{codigo}', 'Detalle con comunas.', null],
    ['GET', '/api/comunas', 'Comunas y localidades.', 'q, municipio, per_page'],
    ['GET', '/api/estadisticas', 'Totales y municipios más poblados.', null],
    ['POST', '/api/tokens', 'Devuelve un token Bearer a partir de email y password.', null],
    ['POST', '/api/{recurso}', 'Crea un registro (requiere token).', null],
    ['PUT', '/api/{recurso}/{codigo}', 'Actualiza un registro (requiere token).', null],
    ['DELETE', '/api/{recurso}/{codigo}', 'Elimina un registro; responde 409 si tiene dependencias.', null],
];
$colores = ['GET' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300', 'POST' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-300', 'PUT' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300', 'DELETE' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300'];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.partials.head', ['title' => 'API'])
    </head>
    <body class="bg-gray-50 font-sans text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <header class="mx-auto flex max-w-5xl items-center justify-between px-6 py-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <x-application-logo class="h-10 w-10" />
                <span class="text-xl font-extrabold tracking-tight">GeoAtlas <span class="font-medium text-gray-400">API</span></span>
            </a>
            <x-geo.theme-toggle />
        </header>

        <main class="mx-auto max-w-5xl space-y-8 px-6 pb-16">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">API REST</h1>
                <p class="mt-2 max-w-2xl text-gray-600 dark:text-gray-400">
                    Todas las respuestas son JSON. La lectura es pública; para crear, editar o eliminar envía el encabezado
                    <code class="rounded bg-gray-200/70 px-1.5 py-0.5 text-sm dark:bg-gray-800">Authorization: Bearer &lt;token&gt;</code>.
                    Límite: 60 peticiones por minuto.
                </p>
            </div>

            <x-geo.card class="overflow-hidden">
                <ul class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($endpoints as [$metodo, $ruta, $descripcion, $parametros])
                        <li class="flex flex-col gap-2 px-5 py-4 sm:flex-row sm:items-center sm:gap-4">
                            <span class="w-16 shrink-0 rounded-lg px-2 py-1 text-center text-xs font-bold {{ $colores[$metodo] }}">{{ $metodo }}</span>
                            <code class="shrink-0 font-mono text-sm font-semibold sm:w-64">{{ $ruta }}</code>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $descripcion }}
                                @if ($parametros)
                                    <span class="block text-xs text-gray-400">Parámetros: {{ $parametros }}</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-geo.card>

            <div class="grid gap-6 lg:grid-cols-2">
                <x-geo.card class="p-5">
                    <h2 class="font-semibold">1. Obtén un token</h2>
                    <pre class="mt-3 overflow-x-auto rounded-xl bg-gray-900 p-4 text-xs leading-6 text-gray-100"><code>curl -X POST {{ url('/api/tokens') }} \
  -H "Accept: application/json" \
  -d email=demo@geoatlas.test \
  -d password=password</code></pre>
                </x-geo.card>
                <x-geo.card class="p-5">
                    <h2 class="font-semibold">2. Úsalo para escribir</h2>
                    <pre class="mt-3 overflow-x-auto rounded-xl bg-gray-900 p-4 text-xs leading-6 text-gray-100"><code>curl -X POST {{ url('/api/municipios') }} \
  -H "Authorization: Bearer &lt;token&gt;" \
  -H "Accept: application/json" \
  -d muni_nomb=Jamundí -d depa_codi=31</code></pre>
                </x-geo.card>
            </div>

            <x-geo.card class="p-5">
                <h2 class="font-semibold">Ejemplo de respuesta · <code class="font-mono text-sm">GET /api/municipios?pais=COL&amp;orden=poblacion&amp;per_page=1</code></h2>
                <pre class="mt-3 overflow-x-auto rounded-xl bg-gray-900 p-4 text-xs leading-6 text-emerald-200"><code>{
  "data": [
    {
      "codigo": 11,
      "nombre": "Bogotá",
      "poblacion": 7900000,
      "departamento_codigo": 5,
      "departamento": { "codigo": 5, "nombre": "Bogotá D.C.", "pais_codigo": "COL" },
      "total_comunas": 20
    }
  ],
  "links": { "...": "..." },
  "meta": { "current_page": 1, "per_page": 1, "total": 67 }
}</code></pre>
            </x-geo.card>
        </main>
    </body>
</html>
