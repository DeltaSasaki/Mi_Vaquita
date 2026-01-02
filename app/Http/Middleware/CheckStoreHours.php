<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Schedule;

class CheckStoreHours
{
    public function handle(Request $request, Closure $next)
    {
        // Si el usuario es Admin, lo dejamos pasar siempre (opcional)
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        if (!Schedule::isOpen()) {
            return redirect()->route('cart.index')->with('error', '⛔ La tienda está cerrada. ' . Schedule::getNextOpenMessage());
        }

        return $next($request);
    }
}