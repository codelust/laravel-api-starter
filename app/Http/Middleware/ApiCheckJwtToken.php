<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Closure;

class ApiCheckJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if(!$user = JWTAuth::parseToken()->toUser()){  
            
            return response()->json(array('error'=>'Please set valid JWT token in the bearer auth'));
        }  

        //$user = JWTAuth::parseToken()->toUser();

        return $next($request);
    }
}
