<?php

namespace App\Http\Middleware;

use App\Classes\JWTAuth;
use App\Classes\MakeResponse;
use App\Http\Controllers\AuthController;
use Closure;
use Illuminate\Http\Request;

class TokenAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('authorization') ?? null;
        $check = (new JWTAuth)->decode($token);
        
        if($check){
            return $next($request);
        }

        return MakeResponse::error()
            ->message('Unauthorized')
            ->code(401)
            ->get();
    }
}
