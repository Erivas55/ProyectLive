<x-layouts::app :title="__('Editar vacante')">
    <div class="mx-auto w-full max-w-2xl">
        <x-page-header title="Editar vacante" />
        <form method="POST" action="{{ route('vacantes.update', $vacante) }}" class="mt-6 space-y-5 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @csrf @method('PUT')
            @include('vacantes._form')
        </form>
    </div>
</x-layouts::app>
