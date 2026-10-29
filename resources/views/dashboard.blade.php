<x-app-layout title="Panel">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header title="Hola, {{ Auth::user()->name }} 👋" subtitle="Este es el resumen de tu atlas geográfico." />

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-geo.stat label="Países" :value="$totales['paises']" :href="route('paises.index')" color="sky" />
            <x-geo.stat label="Departamentos" :value="$totales['departamentos']" :href="route('departamentos.index')" color="emerald" />
            <x-geo.stat label="Municipios" :value="$totales['municipios']" :href="route('municipios.index')" color="amber" />
            <x-geo.stat label="Comunas" :value="$totales['comunas']" :href="route('comunas.index')" color="fuchsia" />
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-geo.card class="p-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Municipios más poblados</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Población total registrada: {{ number_format($poblacion, 0, ',', '.') }} habitantes</p>
                <ol class="mt-4 space-y-3">
                    @foreach ($topMunicipios as $municipio)
                        <li class="flex items-center gap-3">
                            <span class="w-5 text-right text-xs font-semibold text-gray-400">{{ $loop->iteration }}</span>
                            <div class="flex-1">
                                <div class="flex justify-between text-sm">
                                    <a href="{{ route('municipios.show', $municipio) }}" class="geo-link">{{ $municipio->muni_nomb }}</a>
                                    <span class="tabular-nums text-gray-500 dark:text-gray-400">{{ number_format($municipio->muni_pobl, 0, ',', '.') }}</span>
                                </div>
                                <div class="mt-1 h-1.5 rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-1.5 rounded-full bg-gradient-to-r from-emerald-500 to-teal-400"
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
                <ul class="mt-4 divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($departamentosPorPais as $pais)
                        <li class="flex items-center justify-between py-2.5 text-sm">
                            <a href="{{ route('paises.show', $pais) }}" class="geo-link"><span class="geo-badge mr-2">{{ $pais->pais_codi }}</span>{{ $pais->pais_nomb }}</a>
                            <span class="font-semibold tabular-nums text-gray-700 dark:text-gray-300">{{ $pais->departamentos_count }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-geo.card>
        </div>
    </div>
</x-app-layout>
