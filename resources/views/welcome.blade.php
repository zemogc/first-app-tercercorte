<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.partials.head', ['title' => null])
    </head>
    <body class="bg-white font-sans text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <div class="relative isolate overflow-hidden">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl" aria-hidden="true">
                <div class="relative left-1/2 aspect-[1155/678] w-[72rem] -translate-x-1/2 bg-gradient-to-tr from-emerald-300 to-sky-400 opacity-30 dark:opacity-20"
                     style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>

            <header class="mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <x-application-logo class="h-10 w-10" />
                    <span class="text-xl font-extrabold tracking-tight">GeoAtlas</span>
                </a>
                <nav class="flex items-center gap-2">
                    <x-geo.theme-toggle />
                    @auth
                        <x-geo.button :href="route('dashboard')">Ir al panel →</x-geo.button>
                    @else
                        <a href="{{ route('login') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">Iniciar sesión</a>
                        @if (Route::has('register'))
                            <x-geo.button :href="route('register')" class="hidden sm:inline-flex">Crear cuenta</x-geo.button>
                        @endif
                    @endauth
                </nav>
            </header>

            <main>
                <section class="mx-auto max-w-7xl px-6 pb-20 pt-12 text-center lg:px-8 lg:pt-20">
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Laravel 11 · Tailwind CSS · Alpine.js
                    </span>
                    <h1 class="mx-auto mt-6 max-w-4xl text-4xl font-extrabold tracking-tight sm:text-6xl">
                        Tu atlas geográfico de
                        <span class="bg-gradient-to-r from-emerald-500 to-sky-500 bg-clip-text text-transparent">Latinoamérica</span>
                    </h1>
                    <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-gray-600 dark:text-gray-400">
                        Administra países, departamentos, municipios y comunas con una interfaz moderna, estadísticas al instante y una API REST lista para integrarse con otras aplicaciones.
                    </p>
                    <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                        <x-geo.button :href="auth()->check() ? route('dashboard') : route('login')" class="px-6 py-3 text-base">Comenzar ahora</x-geo.button>
                        <x-geo.button variant="secondary" :href="route('api.docs')" class="px-6 py-3 text-base">Ver la API</x-geo.button>
                    </div>

                    <dl class="mx-auto mt-16 grid max-w-3xl grid-cols-2 gap-4 sm:grid-cols-4">
                        @foreach ($totales as $etiqueta => $valor)
                            <div class="rounded-2xl border border-gray-200/70 bg-white/70 p-4 backdrop-blur dark:border-gray-800 dark:bg-gray-900/70">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $etiqueta }}</dt>
                                <dd class="mt-1 text-3xl font-extrabold tracking-tight">{{ $valor }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </section>

                <section class="border-y border-gray-100 bg-gray-50/70 py-20 dark:border-gray-900 dark:bg-gray-900/40">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto max-w-2xl text-center">
                            <h2 class="text-3xl font-bold tracking-tight">Todo lo que necesitas para gestionar territorio</h2>
                            <p class="mt-3 text-gray-600 dark:text-gray-400">Una jerarquía clara de cuatro niveles, conectada de punta a punta.</p>
                        </div>

                        <div class="mx-auto mt-10 flex max-w-3xl flex-wrap items-center justify-center gap-2 text-sm font-semibold">
                            @foreach (['🌎 País', '🗺️ Departamento', '🏙️ Municipio', '📍 Comuna'] as $nivel)
                                <span class="rounded-xl border border-gray-200 bg-white px-4 py-2 shadow-sm dark:border-gray-800 dark:bg-gray-900">{{ $nivel }}</span>
                                @unless ($loop->last)<span class="text-gray-400">→</span>@endunless
                            @endforeach
                        </div>

                        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ([
                                ['📊', 'Panel con estadísticas', 'Totales, municipios más poblados y gráficas interactivas que se adaptan al modo oscuro.'],
                                ['🔎', 'Búsqueda y paginación', 'Encuentra cualquier registro por nombre, código o región en segundos.'],
                                ['🛡️', 'Datos consistentes', 'Validación en español y protección para no borrar registros con dependencias.'],
                                ['🔌', 'API REST', 'Endpoints JSON con filtros, paginación y escritura protegida con tokens Sanctum.'],
                                ['✨', 'Asistente con IA', 'Pregunta en lenguaje natural: Claude consulta la base de datos y te responde con cifras reales.'],
                                ['🇨🇴', 'Datos reales', 'Incluye los 33 departamentos de Colombia y las comunas de Cali, Medellín y Bogotá.'],
                            ] as [$icono, $titulo, $texto])
                                <div class="rounded-2xl border border-gray-200/70 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                                    <div class="text-3xl">{{ $icono }}</div>
                                    <h3 class="mt-4 font-semibold">{{ $titulo }}</h3>
                                    <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ $texto }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </main>

            <footer class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-6 py-10 text-sm text-gray-500 dark:text-gray-400 sm:flex-row lg:px-8">
                <p>© {{ date('Y') }} GeoAtlas · Proyecto académico</p>
                <p>Hecho con Laravel {{ Illuminate\Foundation\Application::VERSION }}</p>
            </footer>
        </div>
    </body>
</html>
