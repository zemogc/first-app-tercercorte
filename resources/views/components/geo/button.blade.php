@props(['variant' => 'primary', 'href' => null])

@php
$clases = 'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 ' . match ($variant) {
    'primary' => 'bg-emerald-600 text-white shadow-sm hover:bg-emerald-500 focus:ring-emerald-500',
    'secondary' => 'border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 focus:ring-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800',
    'danger' => 'bg-rose-600 text-white hover:bg-rose-500 focus:ring-rose-500',
};
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $clases]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $clases]) }}>{{ $slot }}</button>
@endif
