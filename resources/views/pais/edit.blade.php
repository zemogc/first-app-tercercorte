<x-app-layout title="Editar país">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header :title="'Editar ' . $pais->pais_nomb" subtitle="Actualiza el nombre o la capital del país." />

        <x-geo.form-card :action="route('paises.update', $pais)" method="PUT" :cancel="route('paises.index')" submit="Actualizar">
            @include('pais._form', ['pais' => $pais])
        </x-geo.form-card>
    </div>
</x-app-layout>
