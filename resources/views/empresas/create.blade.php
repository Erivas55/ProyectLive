<x-layouts::app :title="__('Nueva empresa')">
    <div class="mx-auto w-full max-w-2xl">
        <x-page-header title="Nueva empresa" />
        <form method="POST" action="{{ route('empresas.store') }}" class="mt-6 space-y-5 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @csrf
            @include('empresas._form')
        </form>
    </div>
</x-layouts::app>
