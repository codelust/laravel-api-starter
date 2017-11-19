<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

	/*prefix for the URL*/

    $api->group(['prefix' => 'v1'], function ($api) {


		$api->resource('users', 'App\Http\Controllers\Api\ApiUserController', ['only' => ['index', 'store']]);
		//Route::apiResource('photo', 'PhotoController');

		$api->post('login', 'App\Http\Controllers\Api\AuthController@login');


     });

});
