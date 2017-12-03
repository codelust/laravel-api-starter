<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

	/*prefix for the URL*/

    $api->group(['prefix' => 'v1'], function ($api) {

    	//user resource. Supported methods: index and store
		$api->resource('users', 'App\Http\Controllers\Api\ApiUserController', ['only' => ['index', 'store']]);

		//login method on Auth
		$api->post('login', 'App\Http\Controllers\Api\AuthController@login');

		//logout method on Auth

		$api->post('logout', 'App\Http\Controllers\Api\AuthController@logout');

		//change password when logged in

		//reset password when not logged in | send the password reset email to user with the link to the reset page from laravel scaffolding

		$api->post('forgot_password', 'App\Http\Controllers\Api\AuthController@fogotPassword');

     });

});
