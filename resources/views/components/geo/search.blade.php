@props(['placeholder' => 'Buscar...'])

<form method="GET" class="relative w-full sm:max-w-xs" role="search">
    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" />
    </svg>
    <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $placeholder }}"
           class="w-full rounded-xl border-gray-200 bg-white py-2 pl-9 pr-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
</form>
