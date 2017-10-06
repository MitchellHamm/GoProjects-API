<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$api = $app->make(Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {
    $api->post('/auth/login', [
        'as' => 'api.auth.login',
        'uses' => 'App\Http\Controllers\Auth\AuthController@postLogin',
    ]);
    $api->post('/test', [
      'as' => 'api.auth.test',
      'uses' => 'App\Http\Controllers\Auth\AuthController@test',
    ]);

    $api->group([
        'middleware' => 'api.auth',
    ], function ($api) {
        //User validation
        $api->get('/auth/user', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@getUser',
            'as' => 'api.auth.user'
        ]);
        $api->patch('/auth/refresh', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@patchRefresh',
            'as' => 'api.auth.refresh'
        ]);
        $api->delete('/auth/invalidate', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@deleteInvalidate',
            'as' => 'api.auth.invalidate'
        ]);


        //Project specific routes
        $api->post('/project/create', [
          'uses' => 'App\Http\Controllers\ProjectController@create',
          'as' => 'api.project.create'
        ]);
        $api->post('/project/edit', [
          'uses' => 'App\Http\Controllers\ProjectController@edit',
          'as' => 'api.project.edit'
        ]);
        $api->post('/project/delete', [
          'uses' => 'App\Http\Controllers\ProjectController@delete',
          'as' => 'api.project.delete'
        ]);
        $api->post('/project/add-member', [
          'uses' => 'App\Http\Controllers\ProjectController@addMember',
          'as' => 'api.project.addMember'
        ]);
        $api->post('/project/request-membership', [
          'uses' => 'App\Http\Controllers\ProjectController@requestMembership',
          'as' => 'api.project.requestMembership'
        ]);

        //User specific routes
        $api->post('/user/search', [
          'uses' => 'App\Http\Controllers\UserController@search',
          'as' => 'api.user.search'
        ]);
    });
});
