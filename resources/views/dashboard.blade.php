<x-layouts::app :title="__('Dashboard')">
    <div class="mx-auto w-full max-w-4xl space-y-6">
        <x-page-header :title="'Hola, '.auth()->user()->name" :subtitle="'Rol: '.(auth()->user()->role?->nombre ?? 'Sin rol asignado')" />
        <div class="rounded-xl border border-zinc-200 bg-white p-6 text-sm text-zinc-700 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300">
            @if (auth()->user()->isAdmin())
                Administras roles, empresas y vacantes, y consultas todas las postulaciones del sistema.
            @elseif (auth()->user()->isReclutador())
                Gestionas las vacantes de {{ auth()->user()->companie?->nombre ?? 'tu empresa' }} y consultas sus postulaciones.
            @elseif (auth()->user()->isCandidato())
                Busca vacantes disponibles y revisa el estado de tus postulaciones.
            @else
                Tu usuario aún no tiene un rol asignado. Contacta a un administrador.
            @endif
        </div>
    </div>
</x-layouts::app>
