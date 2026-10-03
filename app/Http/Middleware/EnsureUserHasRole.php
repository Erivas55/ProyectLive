<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Uso: ->middleware('role:administrador,reclutador')
 * Rechaza con 403 si el usuario autenticado no tiene alguno de los roles indicados.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        abort_unless($request->user()?->hasRole(...$roles), 403, 'No tienes permiso para acceder a esta sección.');

        return $next($request);
    }
}
