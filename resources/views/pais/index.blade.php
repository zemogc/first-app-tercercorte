<x-app-layout title="Países">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header title="Países" subtitle="Países registrados con su capital y número de departamentos.">
            <x-slot name="actions">
                <x-geo.search placeholder="Buscar país o código..." />
                <x-geo.button :href="route('paises.create')">+ Nuevo país</x-geo.button>
            </x-slot>
        </x-geo.page-header>

        <x-geo.flash />

        <x-geo.table :headers="['Código', 'País', 'Capital', 'Departamentos', 'Acciones']" :paginator="$paises">
            @foreach ($paises as $pais)
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40">
                    <td class="geo-td"><span class="geo-badge">{{ $pais->pais_codi }}</span></td>
                    <td class="geo-td"><a href="{{ route('paises.show', $pais) }}" class="geo-link">{{ $pais->pais_nomb }}</a></td>
                    <td class="geo-td">{{ $pais->capital?->muni_nomb ?? '—' }}</td>
                    <td class="geo-td">{{ $pais->departamentos_count }}</td>
                    <td class="geo-td">
                        <x-geo.row-actions :show="route('paises.show', $pais)" :edit="route('paises.edit', $pais)"
                                           :destroy="route('paises.destroy', $pais)" :nombre="$pais->pais_nomb" />
                    </td>
                </tr>
            @endforeach
        </x-geo.table>
    </div>
</x-app-layout>
