<?php

# Backend Auth
Route::auth();

Route::get('oauth/authorize', ['as' => 'oauth.authorize.get', 'middleware' => ['check-authorization-params', 'auth'], function() {
    $authParams = Authorizer::getAuthCodeRequestParams();

    $formParams = array_except($authParams,'client');

    $formParams['client_id'] = $authParams['client']->getId();

    $formParams['scope'] = implode(config('oauth2.scope_delimiter'), array_map(function ($scope) {
        return $scope->getId();
    }, $authParams['scopes']));

    return View::make('oauth.authorization-form', ['params' => $formParams, 'client' => $authParams['client']]);
}]);

Route::post('oauth/authorize', ['as' => 'oauth.authorize.post', 'middleware' => ['csrf', 'check-authorization-params', 'auth'], function() {

    $params = Authorizer::getAuthCodeRequestParams();
    $params['user_id'] = Auth::user()->id;
    $redirectUri = '/';

    // If the user has allowed the client to access its data, redirect back to the client with an auth code.
    if (Request::has('approve')) {
        $redirectUri = Authorizer::issueAuthCode('user', $params['user_id'], $params);
    }

    // If the user has denied the client to access its data, redirect back to the client with an error message.
    if (Request::has('deny')) {
        $redirectUri = Authorizer::authCodeRequestDeniedRedirectUri();
    }

    return Redirect::to($redirectUri);
}]);

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
    Route::get('/credit_notes', 'HomeController@creditNotes');
    Route::get('/purchase_orders', 'HomeController@purchaseOrder');
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@create');
    Route::post('/sales', 'SaleController@create');
    Route::post('/credit_notes', 'SaleController@createCreditNote');
    Route::post('/purchase_orders', 'SaleController@createPurchaseOrder');
    Route::get('/purchase_orders/{sale}', 'SaleController@detailsPurchaseOrder');
    Route::get('/sales/{sale}', 'SaleController@details');
    Route::get('/sales/{sale}/annul', 'SaleController@annulled');
    Route::post('/sales/{sale}/annul', 'SaleController@annulled');
    Route::get('/users/new', 'UserController@newUser');
    Route::post('/users/disenrolled/{user}', 'UserController@disEnrolled');
    Route::get('/users/disenrolled/{user}', 'UserController@disEnrolled');
    Route::get('/users/{user}', 'UserController@details');
    Route::get('/profile/{user}', 'UserController@profile');
    Route::get('/profile/edit/{user}', 'UserController@editProfile');
    Route::put('/profile/{user}', 'UserController@updateProfile');
    Route::get('/members/income/{user}', 'MemberController@membershipIncome');
    Route::get('/providers/income/{user}', 'ProvidersController@membershipIncome');

    Route::post('/users/cbu/{user}', 'UserController@updateCbu');
    Route::post('/users/email/{user}', 'UserController@updateEmail');
    Route::post('/users/code/{user}', 'UserController@updateCode');
    Route::post('/users/administrative_expenses/{user}', 'UserController@updateAdministrativeExpenses');
    Route::get('/users/{property}/{user}', 'UserController@changeProperty');
    Route::get('/users/{property}/confirm/{user}', 'UserController@confirmProperty');
});

# Users
Route::group([],function () {
});


