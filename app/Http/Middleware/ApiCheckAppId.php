<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;

use Closure;

class ApiCheckAppId
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

        if(null === $request->header('X-APP-ID')){  
            return response()->json(array('error'=>'Please set valid X-APP-ID header'));
        }  
  
        /*if($_SERVER['X-API-TOKEN'] != '123456'){  
            return Response::json(array('error'=>'wrong custom header'));  
        }  */
        return $next($request);
    }
}
