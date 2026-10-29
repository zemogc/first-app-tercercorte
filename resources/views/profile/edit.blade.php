<x-app-layout title="Perfil">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-geo.page-header :title="__('Profile')" subtitle="Administra tus datos de acceso." />

        <x-geo.card class="p-4 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </x-geo.card>

        <x-geo.card class="p-4 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </x-geo.card>

        <x-geo.card class="p-4 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </x-geo.card>
    </div>
</x-app-layout>
