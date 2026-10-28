@props(['label', 'value', 'href' => null, 'color' => 'emerald'])

@php
$tonos = [
    'emerald' => 'from-emerald-500 to-teal-500',
    'sky' => 'from-sky-500 to-indigo-500',
    'amber' => 'from-amber-400 to-orange-500',
    'fuchsia' => 'from-fuchsia-500 to-pink-500',
];
@endphp

<a href="{{ $href ?? '#' }}" class="group relative overflow-hidden rounded-2xl border border-gray-200/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br {{ $tonos[$color] }} opacity-15 transition group-hover:scale-110"></div>
    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</p>
    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $value }}</p>
    @isset($icon)
        <div class="absolute right-5 top-5 rounded-xl bg-gradient-to-br {{ $tonos[$color] }} p-2 text-white shadow">{{ $icon }}</div>
    @endisset
</a>
