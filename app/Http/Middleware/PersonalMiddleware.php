<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder.');
        }

        // Verificar que el usuario sea personal de salud (NO estudiante)
        $tiposPermitidos = ['admin', 'medico', 'enfermero', 'recepcionista'];

        if (!in_array(auth()->user()->tipo_usuario, $tiposPermitidos)) {
            abort(403, 'No tienes permiso para acceder a esta sección. Esta área es solo para personal de salud.');
        }

        return $next($request);
    }
}
