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
    Route::get('/register/provider', 'ProvidersController@register');
    Route::post('/register/provider', 'ProvidersController@createRegister');
    Route::get('/user-disable', 'HomeController@userDisable');
});

# Url's common auth
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function ()    {
        return view('welcome');
    });
    Route::get('/home', 'HomeController@index');
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@create');
    Route::get('/users/new', 'UserController@newUser');
    Route::post('/users/cbu/{user}', 'UserController@updateCbu');
    Route::get('/users/cbu/confirm/{user}', 'UserController@confirmCbu');
    Route::get('/users/cbu/{user}', 'UserController@changeCbu');
    Route::post('/users/email/{user}', 'UserController@updateEmail');
    Route::get('/users/email/{user}', 'UserController@changeEmail');
    Route::post('/users/code/{user}', 'UserController@updateCode');
    Route::get('/users/code/{user}', 'UserController@changeCode');
    Route::post('/users/disenrolled/{user}', 'UserController@disEnrolled');
    Route::get('/users/disenrolled/{user}', 'UserController@disEnrolled');
    Route::get('/users/{user}', 'UserController@details');
    Route::get('/profile/{user}', 'UserController@profile');
    Route::get('/profile/edit/{user}', 'UserController@editProfile');
    Route::put('/profile/{user}', 'UserController@updateProfile');
    Route::get('/members/income/{user}', 'MemberController@membershipIncome');
    Route::get('/providers/income/{user}', 'ProvidersController@membershipIncome');
});

# Users
Route::group([],function () {
});


