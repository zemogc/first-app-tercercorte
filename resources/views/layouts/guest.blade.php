<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.partials.head')
    </head>
    <body class="bg-gray-50 font-sans text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <div class="grid min-h-screen lg:grid-cols-2">
            <div class="relative hidden overflow-hidden bg-gradient-to-br from-emerald-600 via-teal-600 to-sky-700 p-12 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -bottom-40 -left-20 h-[28rem] w-[28rem] rounded-full border-[40px] border-white/10"></div>

                <a href="{{ route('home') }}" class="relative flex items-center gap-3">
                    <x-application-logo class="h-11 w-11" />
                    <span class="text-xl font-extrabold tracking-tight">GeoAtlas</span>
                </a>

                <div class="relative max-w-md">
                    <h2 class="text-4xl font-extrabold leading-tight tracking-tight">Explora la geografía de Latinoamérica, región por región.</h2>
                    <p class="mt-4 text-lg text-white/80">Países, departamentos, municipios y comunas en un solo lugar, con estadísticas, una API REST y un asistente de IA que responde preguntas sobre tus datos.</p>
                </div>

                <p class="relative text-sm text-white/60">Proyecto académico · Laravel 12</p>
            </div>

            <div class="flex flex-col items-center justify-center px-6 py-12">
                <div class="absolute right-4 top-4"><x-geo.theme-toggle /></div>

                <a href="{{ route('home') }}" class="mb-8 flex items-center gap-3 lg:hidden">
                    <x-application-logo class="h-11 w-11" />
                    <span class="text-xl font-extrabold tracking-tight">GeoAtlas</span>
                </a>

                <div class="w-full max-w-md rounded-2xl border border-gray-200/70 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
