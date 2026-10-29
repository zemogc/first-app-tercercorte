<x-app-layout title="Nuevo municipio">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header title="Nuevo municipio" subtitle="El código se asigna automáticamente." />

        <x-geo.form-card :action="route('municipios.store')" :cancel="route('municipios.index')">
            @include('municipio._form')
        </x-geo.form-card>
    </div>
</x-app-layout>
