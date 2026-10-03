<?php

namespace App\Policies;

use App\Models\Postulation;
use App\Models\User;

class PostulationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isReclutador() || $user->isCandidato();
    }

    public function view(User $user, Postulation $postulation): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isReclutador()) {
            $empresaId = $postulation->employment?->companie_id;

            return $user->companie_id !== null && $user->companie_id === $empresaId;
        }

        return $user->isCandidato() && $postulation->usuario_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isCandidato();
    }

    /** Retirar una postulación: solo su dueño (candidato). */
    public function delete(User $user, Postulation $postulation): bool
    {
        return $user->isCandidato() && $postulation->usuario_id === $user->id;
    }
}
