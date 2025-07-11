<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Autenticar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $tokenHeader = $request->bearerToken();
        $tokenConfig = config('auth.credentials.token');

        if (!$tokenHeader || $tokenHeader !== $tokenConfig) {
            return response()->json([
                'status' => [
                    'code'      => 401,
                    'message'   => 'Não Autorizado'
                ],
            ], 401);
        }

        return $next($request);
    }
}
