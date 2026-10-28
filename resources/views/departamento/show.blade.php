<x-app-layout :title="$departamento->depa_nomb">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header :title="$departamento->depa_nomb" :subtitle="'Departamento de ' . $departamento->pais?->pais_nomb">
            <x-slot name="actions">
                <x-geo.button variant="secondary" :href="route('departamentos.index')">← Volver</x-geo.button>
                <x-geo.button :href="route('departamentos.edit', $departamento)">Editar</x-geo.button>
            </x-slot>
        </x-geo.page-header>

        <div class="grid gap-4 sm:grid-cols-3">
            <x-geo.stat label="Municipios" :value="$departamento->municipios->count()" color="emerald" />
            <x-geo.stat label="Comunas" :value="$departamento->municipios->sum('comunas_count')" color="fuchsia" />
            <x-geo.stat label="Población registrada" :value="number_format($departamento->municipios->sum('muni_pobl'), 0, ',', '.')" color="amber" />
        </div>

        <x-geo.table :headers="['Municipio', 'Población', 'Comunas', 'Acciones']" empty="Este departamento aún no tiene municipios.">
            @foreach ($departamento->municipios as $municipio)
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40">
                    <td class="geo-td"><a href="{{ route('municipios.show', $municipio) }}" class="geo-link">{{ $municipio->muni_nomb }}</a></td>
                    <td class="geo-td tabular-nums">{{ $municipio->muni_pobl ? number_format($municipio->muni_pobl, 0, ',', '.') : '—' }}</td>
                    <td class="geo-td">{{ $municipio->comunas_count }}</td>
                    <td class="geo-td text-right"><a href="{{ route('municipios.show', $municipio) }}" class="text-sm font-medium text-emerald-600 hover:underline dark:text-emerald-400">Ver →</a></td>
                </tr>
            @endforeach
        </x-geo.table>
    </div>
</x-app-layout>
