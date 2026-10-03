@props(['user'])

<flux:sidebar.nav aria-label="Navegación principal">
    <flux:sidebar.group heading="Mi espacio" class="grid">
        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
            Dashboard
        </flux:sidebar.item>

        <flux:sidebar.item icon="user" :href="route('perfil.create')" :current="request()->routeIs('perfil.*')" wire:navigate>
            Mi perfil
        </flux:sidebar.item>
    </flux:sidebar.group>

    @if ($user->isAdmin())
        <flux:sidebar.group heading="Administración" class="grid">
            <flux:sidebar.item icon="shield-check" :href="route('roles.index')" :current="request()->routeIs('roles.*')" wire:navigate>
                Roles
            </flux:sidebar.item>

            <flux:sidebar.item icon="building-office" :href="route('empresas.index')" :current="request()->routeIs('empresas.*')" wire:navigate>
                Empresas
            </flux:sidebar.item>
        </flux:sidebar.group>
    @endif

    @if ($user->isAdmin() || $user->isReclutador() || $user->isCandidato())
        <flux:sidebar.group heading="Empleos" class="grid">
            <flux:sidebar.item icon="briefcase" :href="route('vacantes.index')" :current="request()->routeIs('vacantes.*')" wire:navigate>
                Vacantes
            </flux:sidebar.item>

            <flux:sidebar.item icon="clipboard-document-list" :href="route('postulaciones.index')" :current="request()->routeIs('postulaciones.*')" wire:navigate>
                Postulaciones
            </flux:sidebar.item>
        </flux:sidebar.group>
    @endif
</flux:sidebar.nav>
