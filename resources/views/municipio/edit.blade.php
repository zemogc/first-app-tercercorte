<x-app-layout title="Editar municipio">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header :title="'Editar ' . $municipio->muni_nomb" :subtitle="'Código #' . $municipio->muni_codi" />

        <x-geo.form-card :action="route('municipios.update', $municipio)" method="PUT" :cancel="route('municipios.index')" submit="Actualizar">
            @include('municipio._form', ['municipio' => $municipio])
        </x-geo.form-card>
    </div>
</x-app-layout>
