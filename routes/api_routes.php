<?php


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->get('users/', function (){
        
        return 'this is it';
    
    });

    $api->group(['prefix' => 'got'], function ($api) {
        // Endpoints registered here will have the "foo" middleware applied.
        //
        //
        // 
        //

        $api->get('users/', function (){

                    return 'this was that';

                        });

             });

});
