<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    


    public function __construct()
    {
        //$this->middleware(['auth.check.app.id', 'auth.check.jwt.token'], ['except' => ['login', 'logout']]);
    }

    /**
     * Login a user.
     *
     * @return \Illuminate\Http\Response
     */

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
        

    	//check if token exists. exit without error if it does not.

    	$token = JWTAuth::getToken();

    	if (!$token) {
    	
    		return response()->json(['message' => 'user logged out'], 200);
    	
    	} else {

    		try {

    			$user = JWTAuth::parseToken()->toUser();
    			
    		} 

    		catch (JWTException $e) {

    			//return response()->json(['invalid_token_error'], $e->getStatusCode());
    			return response()->json(['message' => 'user logged out'], 200);

			} catch (TokenExpiredException $e) {

				//return response()->json(['token_expired'], $e->getStatusCode());
				return response()->json(['message' => 'user logged out'], 200);

			}

			catch (TokenBlackListedException $e) {

				//return response()->json(['token_black_listed'], $e->getStatusCode());
				return response()->json(['message' => 'user logged out'], 200);

			}
				
			JWTAuth::setToken($token)->invalidate();

			return response()->json(['message' => 'user logged out'], 200);
			

    	}
    	
    }

    /**
     * Trigger the forgot password flow
     *
     * @return \Illuminate\Http\Response
     */

    public function fogotPassword(Request $request)
    {

    	$data = json_decode($request->getContent(), true);

        $rules = array('email' => 'required|string|email|max:255|exists:users');

        $validator = Validator::make($data, $rules);
        
        if ($validator->passes()) {

        	// We will send the password reset link to this user. Once we have attempted
	        // to send the link, we will examine the response then see the message we
	        // need to show to the user. Finally, we'll send out a proper response.
	        $response = $this->broker()->sendResetLink(
	            $request->only('email')
	        );

        	$response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($response)
                    : $this->sendResetLinkFailedResponse($request, $response);

             return response()->json(['message' => 'Sent reset email to the email'], 200);

        } else {

        	return response()->json(['error' => 'invalid email'], 404);
        }
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        //return back()->with('status', trans($response));
        return response()->json(['message' => 'Sent reset email to the email'], 200);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse($response)
    {
        //return back()->with('status', trans($response));
        return response()->json(['error' => 'Could not send password reset email'], 200);
    }


    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }


}
