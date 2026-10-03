<x-layouts::app :title="__('Postulación')">
    <div class="mx-auto w-full max-w-3xl space-y-6">
        <x-page-header title="Postulación" :subtitle="$postulacion->employment?->titulo">
            <a href="{{ route('postulaciones.index') }}" class="font-medium text-zinc-700 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">Volver</a>
        </x-page-header>
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 p-6 space-y-3 text-sm text-zinc-700 dark:text-zinc-300">
            <p><strong>Empresa:</strong> {{ $postulacion->employment?->companie?->nombre }}</p>
            <p><strong>Candidato:</strong> {{ $postulacion->usuario?->name }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($postulacion->estado) }}</p>
            <p><strong>Fecha:</strong> {{ $postulacion->fecha_postulacion?->format('d/m/Y H:i') }}</p>
            <p><strong>Carta de presentación:</strong> {{ $postulacion->carta_presentacion ?: '—' }}</p>
        </div>
    </div>
</x-layouts::app>
