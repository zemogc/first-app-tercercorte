<x-app-layout title="Departamentos">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header title="Departamentos" subtitle="Divisiones administrativas de primer nivel de cada país.">
            <x-slot name="actions">
                <x-geo.search placeholder="Buscar departamento o país..." />
                <x-geo.button :href="route('departamentos.create')">+ Nuevo departamento</x-geo.button>
            </x-slot>
        </x-geo.page-header>

        <x-geo.flash />

        <x-geo.table :headers="['Código', 'Departamento', 'País', 'Municipios', 'Acciones']" :paginator="$departamentos">
            @foreach ($departamentos as $departamento)
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40">
                    <td class="geo-td text-gray-400">#{{ $departamento->depa_codi }}</td>
                    <td class="geo-td"><a href="{{ route('departamentos.show', $departamento) }}" class="geo-link">{{ $departamento->depa_nomb }}</a></td>
                    <td class="geo-td"><span class="geo-badge">{{ $departamento->pais_codi }}</span> {{ $departamento->pais?->pais_nomb }}</td>
                    <td class="geo-td">{{ $departamento->municipios_count }}</td>
                    <td class="geo-td">
                        <x-geo.row-actions :show="route('departamentos.show', $departamento)" :edit="route('departamentos.edit', $departamento)"
                                           :destroy="route('departamentos.destroy', $departamento)" :nombre="$departamento->depa_nomb" />
                    </td>
                </tr>
            @endforeach
        </x-geo.table>
    </div>
</x-app-layout>
