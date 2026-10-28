@php
$secciones = [
    ['ruta' => 'dashboard', 'activa' => 'dashboard', 'nombre' => 'Panel',
     'icono' => 'M2.25 12l8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
    ['ruta' => 'paises.index', 'activa' => 'paises.*', 'nombre' => 'Países',
     'icono' => 'M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5'],
    ['ruta' => 'departamentos.index', 'activa' => 'departamentos.*', 'nombre' => 'Departamentos',
     'icono' => 'M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z'],
    ['ruta' => 'municipios.index', 'activa' => 'municipios.*', 'nombre' => 'Municipios',
     'icono' => 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21'],
    ['ruta' => 'comunas.index', 'activa' => 'comunas.*', 'nombre' => 'Comunas',
     'icono' => 'M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z'],
    ['ruta' => 'asistente', 'activa' => 'asistente', 'nombre' => 'Asistente IA',
     'icono' => 'M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z'],
];
@endphp

{{-- Fondo oscuro del menú móvil --}}
<div x-show="sidebar" x-transition.opacity x-cloak @click="sidebar = false" class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm lg:hidden"></div>

<aside :class="sidebar ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-gray-200/70 bg-white transition-transform duration-200 dark:border-gray-800 dark:bg-gray-900 lg:translate-x-0">
    <a href="{{ route('dashboard') }}" class="flex h-16 items-center gap-3 px-6">
        <x-application-logo class="h-9 w-9" />
        <div>
            <p class="text-base font-extrabold leading-tight tracking-tight">GeoAtlas</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Atlas geográfico</p>
        </div>
    </a>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">Explorar</p>
        @foreach ($secciones as $seccion)
            @php($activa = request()->routeIs($seccion['activa']))
            <a href="{{ route($seccion['ruta']) }}"
               @class([
                   'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                   'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' => $activa,
                   'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' => ! $activa,
               ])>
                <svg @class(['h-5 w-5 shrink-0', 'text-emerald-600 dark:text-emerald-400' => $activa, 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' => ! $activa])
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $seccion['icono'] }}" />
                </svg>
                {{ $seccion['nombre'] }}
            </a>
        @endforeach
    </nav>

    <a href="{{ route('api.docs') }}" class="m-3 block rounded-2xl bg-gradient-to-br from-emerald-500 to-sky-600 p-4 text-white transition hover:shadow-lg">
        <p class="text-sm font-semibold">API pública →</p>
        <p class="mt-1 text-xs text-white/80">Consulta los datos en JSON desde <code class="font-mono">/api</code>.</p>
    </a>
</aside>
