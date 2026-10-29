<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.partials.head')
    </head>
    <body class="bg-gray-50 font-sans text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <div x-data="{ sidebar: false }" @keydown.escape.window="sidebar = false" class="min-h-screen">
            @include('layouts.navigation')

            <div class="lg:pl-64">
                <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-gray-200/70 bg-white/80 px-4 backdrop-blur dark:border-gray-800 dark:bg-gray-950/80 sm:px-6 lg:px-8">
                    <button type="button" @click="sidebar = true" class="-ml-1 rounded-xl p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 lg:hidden" aria-label="Abrir menú">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 lg:hidden">
                        <x-application-logo class="h-8 w-8" />
                        <span class="font-bold">GeoAtlas</span>
                    </a>

                    <div class="ml-auto flex items-center gap-1">
                        <x-geo.theme-toggle />

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 rounded-xl px-2 py-1.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-sky-500 text-xs font-bold text-white">
                                        {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                    <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                @isset($header)
                    <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">{{ $header }}</div>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
