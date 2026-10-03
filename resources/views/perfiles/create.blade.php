<x-layouts::app :title="__('Crear perfil')">
    <div class="mx-auto w-full max-w-2xl">
        <x-page-header title="Crear perfil profesional" />
        <form method="POST" action="{{ route('perfil.store') }}" class="mt-6 space-y-5 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @csrf
            @include('perfiles._form')
        </form>
    </div>
</x-layouts::app>
