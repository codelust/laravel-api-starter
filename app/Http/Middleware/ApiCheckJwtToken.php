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

        $token = JWTAuth::getToken();

        if (!$token) {
        
            return response()->json(array('error'=>'Please set valid JWT token in the bearer auth'));
        
        } else {

            try {

                $user = JWTAuth::parseToken()->toUser();
                
            } 

            catch (JWTException $e) {

                return response()->json(array('error'=>'Invalid token'), 401);

            } catch (TokenExpiredException $e) {

                return response()->json(array('error'=>'Token has expired. Please login again'), 401);

            }

            catch (TokenBlackListedException $e) {

                return response()->json(array('error'=>'Token has expired (1). Please login again'), 401);

            }
            

        }

        return $next($request);
    }
}
