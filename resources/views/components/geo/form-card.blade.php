@props(['action', 'method' => 'POST', 'cancel', 'submit' => 'Guardar'])

<x-geo.card class="max-w-2xl">
    <form method="POST" action="{{ $action }}" class="p-6">
        @csrf
        @if (strtoupper($method) !== 'POST')
            @method($method)
        @endif

        <div class="space-y-5">
            {{ $slot }}
        </div>

        <div class="mt-6 flex items-center justify-end gap-2 border-t border-gray-100 pt-5 dark:border-gray-800">
            <x-geo.button variant="secondary" :href="$cancel">Cancelar</x-geo.button>
            <x-geo.button>{{ $submit }}</x-geo.button>
        </div>
    </form>
</x-geo.card>
