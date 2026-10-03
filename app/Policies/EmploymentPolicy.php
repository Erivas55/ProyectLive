<?php

namespace App\Policies;

use App\Models\Employment;
use App\Models\User;

class EmploymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isReclutador() || $user->isCandidato();
    }

    public function view(User $user, Employment $employment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isReclutador()) {
            return $this->belongsToUserCompany($user, $employment);
        }

        // El candidato solo ve vacantes disponibles.
        return $user->isCandidato() && $employment->estado === 'activo';
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || ($user->isReclutador() && $user->companie_id !== null);
    }

    public function update(User $user, Employment $employment): bool
    {
        return $user->isAdmin()
            || ($user->isReclutador() && $this->belongsToUserCompany($user, $employment));
    }

    public function delete(User $user, Employment $employment): bool
    {
        return $this->update($user, $employment);
    }

    private function belongsToUserCompany(User $user, Employment $employment): bool
    {
        return $user->companie_id !== null && $user->companie_id === $employment->companie_id;
    }
}
