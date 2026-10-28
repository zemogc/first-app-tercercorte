@foreach (['success' => 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-200',
           'error' => 'border-rose-200 bg-rose-50 text-rose-800 dark:border-rose-800 dark:bg-rose-950/60 dark:text-rose-200'] as $tipo => $clases)
    @if (session($tipo))
        <div x-data="{ show: true }" x-show="show" x-transition
             class="flex items-start justify-between gap-4 rounded-xl border px-4 py-3 text-sm {{ $clases }}" role="alert">
            <span>{{ session($tipo) }}</span>
            <button type="button" @click="show = false" class="opacity-60 hover:opacity-100" aria-label="Cerrar">&times;</button>
        </div>
    @endif
@endforeach
