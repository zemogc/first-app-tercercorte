@php
$sugerencias = [
    '¿Cuáles son las 5 ciudades más pobladas de Colombia?',
    '¿Cuántas comunas tiene Cali y cuáles son?',
    'Compara Antioquia con el Valle del Cauca',
    '¿Qué departamentos de Ecuador hay registrados?',
];
@endphp

<x-app-layout title="Asistente IA">
    <div x-data="asistente(@js($preguntaInicial))" data-url="{{ route('asistente.preguntar') }}"
         class="mx-auto flex max-w-4xl flex-col px-4 py-8 sm:px-6 lg:px-8" style="min-height: calc(100vh - 4rem)">
        <x-geo.page-header title="Asistente GeoAtlas" subtitle="Pregunta en lenguaje natural. La IA consulta la base de datos para responderte.">
            <x-slot name="actions">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-violet-200 bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700 dark:border-violet-800 dark:bg-violet-950 dark:text-violet-300">
                    ✨ Con Claude
                </span>
                <x-geo.button variant="secondary" type="button" x-show="mensajes.length" x-cloak @click="reiniciar()">Nueva conversación</x-geo.button>
            </x-slot>
        </x-geo.page-header>

        @unless ($configurado)
            <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-950/60 dark:text-amber-200">
                El asistente no está configurado. Agrega <code class="font-mono font-semibold">ANTHROPIC_API_KEY</code> a tu archivo <code class="font-mono">.env</code> para activarlo.
            </div>
        @endunless

        <div class="mt-6 flex-1 space-y-5">
            <template x-if="! mensajes.length && ! cargando">
                <div class="flex flex-col items-center py-10 text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 via-fuchsia-500 to-emerald-400 text-3xl shadow-lg">✨</div>
                    <h2 class="mt-5 text-lg font-semibold text-gray-900 dark:text-white">¿Qué quieres saber?</h2>
                    <p class="mt-1 max-w-md text-sm text-gray-500 dark:text-gray-400">Puedo buscar lugares, comparar regiones, armar rankings de población y listar comunas.</p>
                    <div class="mt-8 grid w-full gap-3 sm:grid-cols-2">
                        @foreach ($sugerencias as $sugerencia)
                            <button type="button" @click="enviar(@js($sugerencia))"
                                    class="rounded-2xl border border-gray-200 bg-white p-4 text-left text-sm text-gray-700 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-violet-700">
                                {{ $sugerencia }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </template>

            <template x-for="(mensaje, i) in mensajes" :key="i">
                <div :class="mensaje.rol === 'user' ? 'flex justify-end' : 'flex gap-3'">
                    <template x-if="mensaje.rol === 'assistant'">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-500 text-sm text-white">✨</div>
                    </template>
                    <div :class="mensaje.rol === 'user'
                            ? 'max-w-[85%] rounded-2xl rounded-br-md bg-emerald-600 px-4 py-2.5 text-sm text-white shadow-sm'
                            : 'min-w-0 max-w-[85%] rounded-2xl rounded-tl-md border border-gray-200/70 bg-white px-4 py-3 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900'">
                        <template x-if="mensaje.rol === 'user'"><p class="whitespace-pre-wrap" x-text="mensaje.texto"></p></template>
                        <template x-if="mensaje.rol === 'assistant'">
                            <div>
                                <div class="geo-prose" x-html="formatear(mensaje.texto)"></div>
                                <div x-show="mensaje.herramientas?.length" class="mt-3 flex flex-wrap gap-1.5 border-t border-gray-100 pt-3 dark:border-gray-800">
                                    <template x-for="h in [...new Set(mensaje.herramientas)]" :key="h">
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-gray-100 px-2 py-0.5 text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" /></svg>
                                            <span x-text="etiqueta(h)"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="cargando" x-cloak class="flex gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-500 text-sm text-white">✨</div>
                <div class="flex items-center gap-1.5 rounded-2xl rounded-tl-md border border-gray-200/70 bg-white px-4 py-3.5 dark:border-gray-800 dark:bg-gray-900">
                    <span class="h-2 w-2 animate-bounce rounded-full bg-violet-400 [animation-delay:-0.3s]"></span>
                    <span class="h-2 w-2 animate-bounce rounded-full bg-violet-400 [animation-delay:-0.15s]"></span>
                    <span class="h-2 w-2 animate-bounce rounded-full bg-violet-400"></span>
                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">Consultando el atlas...</span>
                </div>
            </div>

            <div x-show="error" x-cloak class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-800 dark:bg-rose-950/60 dark:text-rose-200" x-text="error"></div>
            <div x-ref="fin"></div>
        </div>

        <form @submit.prevent="enviar()" class="sticky bottom-4 mt-6">
            <div class="flex items-end gap-2 rounded-2xl border border-gray-200 bg-white p-2 shadow-lg dark:border-gray-800 dark:bg-gray-900">
                <textarea x-model="entrada" rows="1" maxlength="2000" placeholder="Escribe tu pregunta..."
                          @keydown.enter.prevent.exact="enviar()"
                          @input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 160) + 'px'"
                          class="max-h-40 flex-1 resize-none border-0 bg-transparent px-2 py-2 text-sm focus:ring-0 dark:text-gray-100"></textarea>
                <button type="submit" :disabled="cargando || ! entrada.trim()"
                        class="rounded-xl bg-gradient-to-br from-violet-600 to-fuchsia-600 p-2.5 text-white shadow transition hover:opacity-90 disabled:opacity-40" aria-label="Enviar">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                </button>
            </div>
            <p class="mt-2 text-center text-xs text-gray-400">Las poblaciones son aproximadas. La IA puede cometer errores.</p>
        </form>
    </div>
</x-app-layout>
