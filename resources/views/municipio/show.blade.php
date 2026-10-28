<x-app-layout :title="$municipio->muni_nomb">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header :title="$municipio->muni_nomb"
                           :subtitle="$municipio->departamento?->depa_nomb . ', ' . $municipio->departamento?->pais?->pais_nomb">
            <x-slot name="actions">
                <x-geo.button variant="secondary" :href="route('municipios.index')">← Volver</x-geo.button>
                <x-geo.button :href="route('municipios.edit', $municipio)">Editar</x-geo.button>
            </x-slot>
        </x-geo.page-header>

        <div class="grid gap-4 sm:grid-cols-2">
            <x-geo.stat label="Población aproximada" :value="$municipio->muni_pobl ? number_format($municipio->muni_pobl, 0, ',', '.') : 'Sin dato'" color="amber" />
            <x-geo.stat label="Comunas / localidades" :value="$municipio->comunas->count()" color="fuchsia" :href="route('comunas.index', ['q' => $municipio->muni_nomb])" />
        </div>

        <x-geo.card class="p-6">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Comunas</h2>
            @if ($municipio->comunas->isEmpty())
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Este municipio no tiene comunas registradas.</p>
            @else
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($municipio->comunas as $comuna)
                        <a href="{{ route('comunas.edit', $comuna) }}"
                           class="rounded-xl border border-gray-200 px-3 py-1.5 text-sm text-gray-700 transition hover:border-emerald-400 hover:text-emerald-700 dark:border-gray-700 dark:text-gray-300 dark:hover:border-emerald-500 dark:hover:text-emerald-300">
                            {{ $comuna->comu_nomb }}
                        </a>
                    @endforeach
                </div>
            @endif
        </x-geo.card>
    </div>
</x-app-layout>
