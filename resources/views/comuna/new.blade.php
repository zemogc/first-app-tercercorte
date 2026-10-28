<x-app-layout title="Nueva comuna">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header title="Nueva comuna" subtitle="El código se asigna automáticamente." />

        <x-geo.form-card :action="route('comunas.store')" :cancel="route('comunas.index')">
            @include('comuna._form')
        </x-geo.form-card>
    </div>
</x-app-layout>
