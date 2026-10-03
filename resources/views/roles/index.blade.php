<x-layouts::app :title="__('Roles')">
    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-page-header title="Roles" subtitle="Roles disponibles para los usuarios de la plataforma.">
            <a href="{{ route('roles.create') }}" class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">Nuevo rol</a>
        </x-page-header>
        @include('partials.flash')
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800/70"><tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Nombre</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Descripción</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300 text-right">Acciones</th>
                </tr></thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300 font-medium">{{ $role->nombre }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">{{ $role->descripcion ?: 'Sin descripción' }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300 text-right"><div class="flex justify-end gap-3">
                            <a href="{{ route('roles.edit', $role) }}" class="font-medium text-zinc-700 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">Editar</a>
                            @unless ($role->isBase())
                                <form method="POST" action="{{ route('roles.destroy', $role) }}" onsubmit="return confirm('¿Eliminar el rol {{ $role->nombre }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                                </form>
                            @endunless
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-6 py-10 text-center text-sm text-zinc-500">Todavía no hay roles.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
