<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAuditor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder.');
        }

        // Verificar que el rol sea 2 (Auditor)
        // Roles: 1=Administrador, 2=Auditor, 3=Recepcionista
        if (auth()->user()->rol !== 2) {
            abort(403, 'Acceso no autorizado. Solo auditores pueden acceder a esta sección.');
        }

        return $next($request);
    }
}