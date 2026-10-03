<x-layouts::app :title="__('Mi perfil')">
    <div class="mx-auto w-full max-w-2xl space-y-6">
        <x-page-header title="Mi perfil" :subtitle="auth()->user()->name">
            <a href="{{ route('perfil.edit', $perfil) }}" class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">Editar perfil</a>
        </x-page-header>
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 p-6 space-y-3 text-sm text-zinc-700 dark:text-zinc-300">
            <p><strong>Profesión:</strong> {{ $perfil->profesion }}</p>
            <p><strong>Teléfono:</strong> {{ $perfil->telefono ?: '—' }}</p>
            <p><strong>Biografía:</strong> {{ $perfil->biografia ?: '—' }}</p>
        </div>
    </div>
</x-layouts::app>
