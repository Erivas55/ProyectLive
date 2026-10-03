<x-layouts::app :title="__('Vacantes')">
    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-page-header title="Vacantes" :subtitle="auth()->user()->isCandidato() ? 'Vacantes disponibles para postularte.' : 'Vacantes publicadas.'">
            @can('create', App\Models\Employment::class)
                <a href="{{ route('vacantes.create') }}" class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">Nueva vacante</a>
            @endcan
        </x-page-header>
        @include('partials.flash')
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800/70"><tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Título</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Empresa</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Modalidad</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Estado</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300 text-right">Acciones</th>
                </tr></thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($vacantes as $vacante)
                    <tr>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300 font-medium">{{ $vacante->titulo }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">{{ $vacante->companie?->nombre }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">{{ ucfirst($vacante->modalidad) }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">{{ ucfirst($vacante->estado) }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300 text-right"><div class="flex justify-end gap-3">
                            <a href="{{ route('vacantes.show', $vacante) }}" class="font-medium text-zinc-700 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">Ver</a>
                            @can('update', $vacante)
                                <a href="{{ route('vacantes.edit', $vacante) }}" class="font-medium text-zinc-700 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">Editar</a>
                            @endcan
                            @can('delete', $vacante)
                                <form method="POST" action="{{ route('vacantes.destroy', $vacante) }}" onsubmit="return confirm('¿Eliminar esta vacante?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                                </form>
                            @endcan
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-sm text-zinc-500">No hay vacantes para mostrar.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
