<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|Exception|TokenExpiredException|TokenInvalidException
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        }
        catch (Exception $e){
            return response()->json(['error' => 'token not found']);
        }
        catch (TokenInvalidException $e){
            return response()->json(['error' => 'invalid token']);
        }
        catch (TokenExpiredException $e){
            return response()->json(['error' => 'expired token']);
        }

        return $next($request);
    }
}
