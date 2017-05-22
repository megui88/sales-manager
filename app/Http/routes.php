<?php

# Backend Auth
Route::auth();

Route::get('oauth/authorize', [
    'as' => 'oauth.authorize.get',
    'middleware' => ['check-authorization-params', 'auth'],
    function () {
        $authParams = Authorizer::getAuthCodeRequestParams();

        $formParams = array_except($authParams, 'client');

        $formParams['client_id'] = $authParams['client']->getId();

        $formParams['scope'] = implode(config('oauth2.scope_delimiter'), array_map(function ($scope) {
            return $scope->getId();
        }, $authParams['scopes']));

        return View::make('oauth.authorization-form', ['params' => $formParams, 'client' => $authParams['client']]);
    }
]);

Route::post('oauth/authorize', [
    'as' => 'oauth.authorize.post',
    'middleware' => ['csrf', 'check-authorization-params', 'auth'],
    function () {

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
    }
]);

# Api OAuth
Route::get('oauth/access_token', function () {
    return Response::json(Authorizer::issueAccessToken());
});

Route::post('oauth/access_token', function () {
    return Response::json(Authorizer::issueAccessToken());
});

# Url's anonymous
Route::group([], function () {
    Route::get('/register/provider', 'ProvidersController@register');
    Route::post('/register/provider', 'ProvidersController@createRegister');
    Route::get('/user-disable', 'HomeController@userDisable');
    Route::get('/un-authorization', 'HomeController@unAuthorization');
});

# Url's pharmacy auth
Route::group(['middleware' => ['auth', 'role:' . \App\Services\BusinessCore::PHARMACIST_ROLE]], function () {
    Route::get('/pharmacy', 'HomeController@pharmacy');
    Route::post('/pharmacy/file', 'MigrateController@pharmacyFile');
    Route::get('/users/{user}', 'UserController@details');
});

# Url's members auth
Route::group(['middleware' => ['auth', 'role:' . \App\Services\BusinessCore::MEMBER_ROLE]], function () {
    Route::get('/', 'HomeController@init');
    Route::get('/details', 'HomeController@details');
    Route::get('/details/M7M/{init}/{done}', 'UserController@account0Details');
    Route::get('/details/{user}/{init}/{done}', 'UserController@accountDetails');
});

# Url's administrator auth
Route::group(['middleware' => ['auth', 'role:' . \App\Services\BusinessCore::EMPLOYEE_ADMIN_ROLE]], function () {
    Route::get('/budget', 'HomeController@budget');
    Route::get('/close', 'HomeController@close');
    Route::post('/close/{step}', 'CloseController@step');
    Route::get('/satellite', 'HomeController@satellite');
    Route::get('/others', 'HomeController@others');
    Route::post('/satellite', 'SatelliteController@downloadFile');
    Route::post('/others', 'SatelliteController@othersFile');
    Route::get('/axoft_import', 'HomeController@AxoftImport');
    Route::post('/axoft_import/file', 'MigrateController@AxoftImportFile');
});
# Url's common auth
Route::group(['middleware' => ['auth']], function () {
    Route::get('/users', 'UserController@index');
    Route::get('/users/{user}', 'UserController@details');
    Route::get('/sales/{sale}', 'SaleController@details');
    Route::get('/migrate/file/{migrate}/errors', 'MigrateController@errorsFile');
});

# Url's common auth
Route::group(['middleware' => ['auth', 'role:' . \App\Services\BusinessCore::EMPLOYEE_ROLE]], function () {


    Route::get('/on-limit', 'HomeController@onLimit');
    Route::get('/home', 'HomeController@index');
    Route::get('/credit_notes', 'HomeController@creditNotes');
    Route::get('/purchase_orders', 'HomeController@purchaseOrder');
    Route::get('/check_book', 'HomeController@checkBook');
    Route::post('/check_book', 'SaleController@createSupplier');
    Route::post('/users', 'UserController@create');
    Route::post('/sales', 'SaleController@create');
    Route::post('/credit_notes', 'SaleController@createCreditNote');
    Route::post('/purchase_orders', 'SaleController@createPurchaseOrder');
    Route::patch('/purchase_orders', 'SaleController@confirmPurchaseOrder');
    Route::get('/purchase_orders/{sale}', 'SaleController@detailsPurchaseOrder');
    Route::get('/sales/{sale}/annul', 'SaleController@annulled');
    Route::post('/sales/{sale}/annul', 'SaleController@annulled');

    Route::get('/users-new', 'UserController@newUser');
    Route::post('/users/disenrolled/{user}', 'UserController@disEnrolled');
    Route::get('/users/disenrolled/{user}', 'UserController@disEnrolled');
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


    Route::get('/bulk_import', 'HomeController@bulkImport');
    Route::post('/bulk_import/file', 'MigrateController@bulkImportFile');
});

# Users
Route::group([], function () {
});


