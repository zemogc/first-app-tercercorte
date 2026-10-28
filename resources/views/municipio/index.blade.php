<x-app-layout title="Municipios">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header title="Municipios" subtitle="Municipios y ciudades con su población aproximada.">
            <x-slot name="actions">
                <x-geo.search placeholder="Buscar municipio o departamento..." />
                <x-geo.button :href="route('municipios.create')">+ Nuevo municipio</x-geo.button>
            </x-slot>
        </x-geo.page-header>

        <x-geo.flash />

        <x-geo.table :headers="['Código', 'Municipio', 'Departamento', 'Población', 'Comunas', 'Acciones']" :paginator="$municipios">
            @foreach ($municipios as $municipio)
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40">
                    <td class="geo-td text-gray-400">#{{ $municipio->muni_codi }}</td>
                    <td class="geo-td"><a href="{{ route('municipios.show', $municipio) }}" class="geo-link">{{ $municipio->muni_nomb }}</a></td>
                    <td class="geo-td">
                        {{ $municipio->departamento?->depa_nomb }}
                        <span class="geo-badge ml-1">{{ $municipio->departamento?->pais_codi }}</span>
                    </td>
                    <td class="geo-td tabular-nums">{{ $municipio->muni_pobl ? number_format($municipio->muni_pobl, 0, ',', '.') : '—' }}</td>
                    <td class="geo-td">{{ $municipio->comunas_count }}</td>
                    <td class="geo-td">
                        <x-geo.row-actions :show="route('municipios.show', $municipio)" :edit="route('municipios.edit', $municipio)"
                                           :destroy="route('municipios.destroy', $municipio)" :nombre="$municipio->muni_nomb" />
                    </td>
                </tr>
            @endforeach
        </x-geo.table>
    </div>
</x-app-layout>
