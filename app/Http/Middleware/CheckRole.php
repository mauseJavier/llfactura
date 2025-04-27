<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario autenticado tiene role_id = 3
        if (auth()->check() && auth()->user()->role_id === 3) {
            return $next($request);
        }

        // Retornar respuesta de acceso denegado
        return response()->json(['message' => 'Acceso denegado.'], 403);
    }
}