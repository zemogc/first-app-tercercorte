@props(['headers' => [], 'paginator' => null, 'empty' => 'No hay registros para mostrar.'])

<x-geo.card class="overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
            <thead class="bg-gray-50/80 dark:bg-gray-800/50">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 {{ $loop->last ? 'text-right' : '' }}">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @if (! $slot->hasActualContent())
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">{{ $empty }}</td>
                    </tr>
                @else
                    {{ $slot }}
                @endif
            </tbody>
        </table>
    </div>
    @if ($paginator && $paginator->hasPages())
        <div class="border-t border-gray-100 px-5 py-3 dark:border-gray-800">
            {{ $paginator->links() }}
        </div>
    @endif
</x-geo.card>
