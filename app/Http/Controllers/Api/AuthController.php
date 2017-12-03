<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;

class AuthController extends Controller
{
    /**
     * Login a user.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct()
    {
        //$this->middleware(['auth.check.app.id', 'auth.check.jwt.token'], ['except' => ['login', 'logout']]);
    }

    public function login(Request $request)
    {
        //
        $data = json_decode($request->getContent(), true);

        $rules = array('email' => 'required|string|email|max:255|exists:users',
            		'password' => 'required|string|min:6');

        $validator = Validator::make($data, $rules);
        
        if ($validator->passes()) {

    		try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($data)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
	            }
	        } catch (JWTException $e) {
	            // something went wrong whilst attempting to encode the token
	            return response()->json(['error' => 'could_not_create_token'], 500);
	        }

	        // all good so return the token

	        $user = User::where('email', $data['email'])->first();

	        $user->token = $token;

	        return response()->json($user);

        	

        } else {
            //TODO Handle your error
            dd($validator->errors()->all());
        }
    }

    /**
     * Login a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        //

    	dd(JWTAuth::parseToken());

    	if($user = JWTAuth::parseToken()->toUser())
    		{


    			//dd($user);

    			$token = JWTAuth::getToken();


					if ($token) {
					    
					    if (JWTAuth::setToken($token)->invalidate()) {

					    	return response()->json(['message' => 'user logged out'], 200);
					    }
					}	
    		}

         else {
            //TODO Handle your error
            //dd($validator->errors()->all());
        }
    }
}
