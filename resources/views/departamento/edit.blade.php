<x-app-layout title="Editar departamento">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header :title="'Editar ' . $departamento->depa_nomb" :subtitle="'Código #' . $departamento->depa_codi" />

        <x-geo.form-card :action="route('departamentos.update', $departamento)" method="PUT" :cancel="route('departamentos.index')" submit="Actualizar">
            @include('departamento._form', ['departamento' => $departamento])
        </x-geo.form-card>
    </div>
</x-app-layout>
