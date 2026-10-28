<x-app-layout title="Comunas">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header title="Comunas" subtitle="Comunas y localidades en las que se dividen los municipios.">
            <x-slot name="actions">
                <x-geo.search placeholder="Buscar comuna o municipio..." />
                <x-geo.button :href="route('comunas.create')">+ Nueva comuna</x-geo.button>
            </x-slot>
        </x-geo.page-header>

        <x-geo.flash />

        <x-geo.table :headers="['Código', 'Comuna', 'Municipio', 'Departamento', 'Acciones']" :paginator="$comunas">
            @foreach ($comunas as $comuna)
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40">
                    <td class="geo-td text-gray-400">#{{ $comuna->comu_codi }}</td>
                    <td class="geo-td font-medium text-gray-900 dark:text-white">{{ $comuna->comu_nomb }}</td>
                    <td class="geo-td"><a href="{{ route('municipios.show', $comuna->muni_codi) }}" class="geo-link font-normal">{{ $comuna->municipio?->muni_nomb }}</a></td>
                    <td class="geo-td">{{ $comuna->municipio?->departamento?->depa_nomb }}</td>
                    <td class="geo-td">
                        <x-geo.row-actions :edit="route('comunas.edit', $comuna)" :destroy="route('comunas.destroy', $comuna)" :nombre="$comuna->comu_nomb" />
                    </td>
                </tr>
            @endforeach
        </x-geo.table>
    </div>
</x-app-layout>
