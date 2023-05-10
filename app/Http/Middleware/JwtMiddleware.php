<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        logger($request);
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if($e instanceof TokenInvalidException){
                return response()->json(['error'=>'Token Invalido'], 401);
            }
            if($e instanceof TokenExpiredException){
                return response()->json(['error'=>'El token ha vencido'], 401);
            }
            return response()->json(['error'=>'Token no encontrado'], 401);
        }
        return $next($request);
    }
}
