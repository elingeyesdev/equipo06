<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  list<string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(403, 'Acceso denegado.');
        }

        $rol = (string) ($user->rol ?? 'productor');
        if (! in_array($rol, $roles, true)) {
            abort(403, 'Acceso denegado.');
        }

        return $next($request);
    }
}
