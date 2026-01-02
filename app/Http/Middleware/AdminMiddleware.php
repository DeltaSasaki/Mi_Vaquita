<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificamos si está logueado Y si es administrador
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Si no cumple, lo mandamos al inicio
        return redirect('/')->with('error', 'No tienes permiso para acceder aquí.');
    }
}