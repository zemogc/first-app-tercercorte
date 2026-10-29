<x-app-layout title="Nuevo departamento">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header title="Nuevo departamento" subtitle="El código se asigna automáticamente." />

        <x-geo.form-card :action="route('departamentos.store')" :cancel="route('departamentos.index')">
            @include('departamento._form')
        </x-geo.form-card>
    </div>
</x-app-layout>
