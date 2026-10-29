<x-app-layout :title="$pais->pais_nomb">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header :title="$pais->pais_nomb" :subtitle="'Código ' . $pais->pais_codi . ' · Capital: ' . ($pais->capital?->muni_nomb ?? 'sin asignar')">
            <x-slot name="actions">
                <x-geo.button variant="secondary" :href="route('paises.index')">← Volver</x-geo.button>
                <x-geo.button :href="route('paises.edit', $pais)">Editar</x-geo.button>
            </x-slot>
        </x-geo.page-header>

        <div class="grid gap-4 sm:grid-cols-3">
            <x-geo.stat label="Departamentos" :value="$pais->departamentos->count()" color="sky" />
            <x-geo.stat label="Municipios" :value="$pais->departamentos->sum('municipios_count')" color="emerald" />
            <x-geo.stat label="Población registrada" :value="number_format($poblacion, 0, ',', '.')" color="amber" />
        </div>

        <x-geo.table :headers="['Departamento', 'Municipios', 'Acciones']" empty="Este país aún no tiene departamentos.">
            @foreach ($pais->departamentos as $departamento)
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40">
                    <td class="geo-td"><a href="{{ route('departamentos.show', $departamento) }}" class="geo-link">{{ $departamento->depa_nomb }}</a></td>
                    <td class="geo-td">{{ $departamento->municipios_count }}</td>
                    <td class="geo-td text-right"><a href="{{ route('departamentos.show', $departamento) }}" class="text-sm font-medium text-emerald-600 hover:underline dark:text-emerald-400">Ver →</a></td>
                </tr>
            @endforeach
        </x-geo.table>
    </div>
</x-app-layout>
