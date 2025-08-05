<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireAdmin
{
    public function handle(Request $request, Closure $next)
    {

        if (!$request->has('employee')) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso no autorizado.'
            ], 401);
        }


        if (!$request->get('is_admin', false)) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado. Se requiere rol de Administrador.'
            ], 403);
        }

        return $next($request);
    }
}