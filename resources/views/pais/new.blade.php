<x-app-layout title="Nuevo país">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header title="Nuevo país" subtitle="Registra un país y, si quieres, su capital." />

        <x-geo.form-card :action="route('paises.store')" :cancel="route('paises.index')">
            @include('pais._form')
        </x-geo.form-card>
    </div>
</x-app-layout>
