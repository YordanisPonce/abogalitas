<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!auth()->user()->is_admin) {
            return ResponseHelper::response(ResponseHelper::fail("Usted no tiene permisos para ejecutar esta operación", Response::HTTP_FORBIDDEN));
        }

        return $next($request);
    }
}
