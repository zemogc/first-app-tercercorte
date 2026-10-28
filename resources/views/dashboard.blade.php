<x-app-layout title="Panel">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header title="Hola, {{ Auth::user()->name }} 👋" subtitle="Este es el resumen de tu atlas geográfico.">
            <x-slot name="actions">
                <x-geo.button variant="secondary" :href="route('municipios.create')">+ Municipio</x-geo.button>
                <x-geo.button :href="route('paises.create')">+ País</x-geo.button>
            </x-slot>
        </x-geo.page-header>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-geo.stat label="Países" :value="$totales['paises']" :href="route('paises.index')" color="sky">
                <x-slot name="icon"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5" /></svg></x-slot>
            </x-geo.stat>
            <x-geo.stat label="Departamentos" :value="$totales['departamentos']" :href="route('departamentos.index')" color="emerald">
                <x-slot name="icon"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" /></svg></x-slot>
            </x-geo.stat>
            <x-geo.stat label="Municipios" :value="$totales['municipios']" :href="route('municipios.index')" color="amber">
                <x-slot name="icon"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg></x-slot>
            </x-geo.stat>
            <x-geo.stat label="Comunas" :value="$totales['comunas']" :href="route('comunas.index')" color="fuchsia">
                <x-slot name="icon"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg></x-slot>
            </x-geo.stat>
        </div>

        <x-geo.card class="p-6">
            <div class="flex flex-wrap items-baseline justify-between gap-2">
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Población por departamento</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Los 10 departamentos con más habitantes registrados.</p>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total: <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($poblacion, 0, ',', '.') }}</span> habitantes</p>
            </div>
            <div class="mt-6 h-72">
                <canvas data-chart="bar" data-values='@json($graficaPoblacion)' aria-label="Gráfica de población por departamento" role="img"></canvas>
            </div>
        </x-geo.card>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-geo.card class="p-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Municipios más poblados</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Ranking según la población aproximada.</p>
                <ol class="mt-5 space-y-3.5">
                    @foreach ($topMunicipios as $municipio)
                        <li class="flex items-center gap-3">
                            <span class="w-5 text-right text-xs font-semibold text-gray-400">{{ $loop->iteration }}</span>
                            <div class="flex-1">
                                <div class="flex justify-between text-sm">
                                    <a href="{{ route('municipios.show', $municipio) }}" class="geo-link">
                                        {{ $municipio->muni_nomb }}
                                        <span class="ml-1 text-xs font-normal text-gray-400">{{ $municipio->departamento?->pais_codi }}</span>
                                    </a>
                                    <span class="tabular-nums text-gray-500 dark:text-gray-400">{{ number_format($municipio->muni_pobl, 0, ',', '.') }}</span>
                                </div>
                                <div class="mt-1.5 h-1.5 rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-1.5 rounded-full bg-gradient-to-r from-emerald-500 to-sky-400"
                                         style="width: {{ max(2, round($municipio->muni_pobl / max(1, $topMunicipios->first()->muni_pobl) * 100)) }}%"></div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </x-geo.card>

            <x-geo.card class="p-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Departamentos por país</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Cuántas divisiones registra cada país.</p>
                <div class="mt-6 h-64">
                    <canvas data-chart="doughnut" data-values='@json($graficaPaises)' aria-label="Gráfica de departamentos por país" role="img"></canvas>
                </div>
            </x-geo.card>
        </div>
    </div>
</x-app-layout>
