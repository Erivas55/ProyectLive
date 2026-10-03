<x-layouts::app :title="$vacante->titulo">
    <div class="mx-auto w-full max-w-3xl space-y-6">
        <x-page-header :title="$vacante->titulo" :subtitle="$vacante->companie?->nombre">
            <a href="{{ route('vacantes.index') }}" class="font-medium text-zinc-700 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">Volver</a>
        </x-page-header>
        @include('partials.flash')
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 p-6 space-y-3 text-sm text-zinc-700 dark:text-zinc-300">
            <p>{{ $vacante->descripcion ?: 'Sin descripción.' }}</p>
            <p><strong>Ubicación:</strong> {{ $vacante->ubicacion ?: '—' }}</p>
            <p><strong>Modalidad:</strong> {{ ucfirst($vacante->modalidad) }} · <strong>Contrato:</strong> {{ str_replace('_', ' ', $vacante->tipo_contrato) }}</p>
            <p><strong>Salario:</strong> {{ $vacante->salario ? 'Q'.number_format($vacante->salario, 2) : 'No especificado' }}</p>
        </div>
        @can('create', App\Models\Postulation::class)
            @if ($yaPostulado)
                <p class="text-sm text-emerald-700 dark:text-emerald-400">Ya te postulaste a esta vacante.</p>
            @else
                <form method="POST" action="{{ route('postulaciones.store', $vacante) }}" class="mt-6 space-y-5 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 !mt-0">
                    @csrf
                    <x-field name="carta_presentacion" label="Carta de presentación (opcional)" type="textarea" />
                    <div class="flex items-center justify-end gap-3 border-t border-zinc-200 pt-5 dark:border-zinc-700"><button type="submit" class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">Postularme</button></div>
                </form>
            @endif
        @endcan
    </div>
</x-layouts::app>
