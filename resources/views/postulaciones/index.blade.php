<x-layouts::app :title="__('Postulaciones')">
    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-page-header title="Postulaciones" :subtitle="auth()->user()->isCandidato() ? 'Tus postulaciones.' : (auth()->user()->isReclutador() ? 'Postulaciones recibidas en tu empresa.' : 'Postulaciones del sistema.')" />
        @include('partials.flash')
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800/70"><tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Vacante</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Empresa</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Candidato</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Estado</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300 text-right">Acciones</th>
                </tr></thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($postulaciones as $p)
                    <tr>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300 font-medium">{{ $p->employment?->titulo }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">{{ $p->employment?->companie?->nombre }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">{{ $p->usuario?->name }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">{{ ucfirst($p->estado) }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300 text-right"><div class="flex justify-end gap-3">
                            <a href="{{ route('postulaciones.show', $p) }}" class="font-medium text-zinc-700 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">Ver</a>
                            @can('delete', $p)
                                <form method="POST" action="{{ route('postulaciones.destroy', $p) }}" onsubmit="return confirm('¿Retirar esta postulación?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Retirar</button>
                                </form>
                            @endcan
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-sm text-zinc-500">No hay postulaciones para mostrar.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
