<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Adicione esta linha


class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário está autenticado e se é um administrador
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        abort(403, 'Acesso negado');
    }
}