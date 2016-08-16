<?php

# Backend Auth
Route::auth();

# Api OAuth
Route::get('oauth/access_token', function () {
    return Response::json(Authorizer::issueAccessToken());
});

Route::post('oauth/access_token', function () {
    return Response::json(Authorizer::issueAccessToken());
});

# Url's anonymous
Route::group([],function () {
    Route::get('/home', 'HomeController@index');
});

# Url's common auth
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function ()    {
        return view('welcome');
    });
});

# Users
Route::group([],function () {
    Route::get('/users', 'UserController@index');
});


