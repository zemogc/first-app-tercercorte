<x-app-layout title="Editar comuna">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header :title="'Editar ' . $comuna->comu_nomb" :subtitle="'Código #' . $comuna->comu_codi" />

        <x-geo.form-card :action="route('comunas.update', $comuna)" method="PUT" :cancel="route('comunas.index')" submit="Actualizar">
            @include('comuna._form', ['comuna' => $comuna])
        </x-geo.form-card>
    </div>
</x-app-layout>
