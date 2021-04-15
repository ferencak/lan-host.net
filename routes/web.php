<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


View::composer('*', function($view)
{
    $view->with('clientController', new \App\Http\Controllers\ClientController());
    $view->with('orderableController', new \App\Http\Controllers\OrderableController());

    if(isset($_SESSION['user']))
    {

        $client = DB::table('clients')->where('id', $_SESSION['user']->id)->get();
        $data = json_decode($client[0]->data, true);
        if(json_decode($_SESSION['user']->data, true)['client_info']['email'] !== $data['client_info']['email'] || json_decode($_SESSION['user']->data, true)['client_data']['unique_id'] !== $data['client_data']['unique_id'] || json_decode($_SESSION['user']->data, true)['client_info']['password'] !== $data['client_info']['password'])
        {
            $data1 = json_decode($_SESSION['user']->data, true);
            $data1['client_info']['email'] = $data['client_info']['email'] ;
            $_SESSION['user']->data = json_encode($data1);
            \App\Http\Controllers\ClientController::logout();
            die();
        }
    }
});

Route::get('/', 'ApplicationController@view');
Route::any('/client/login/{cancel?}', 'ApplicationController@view_login');
Route::any('/client/register', 'ApplicationController@view_register');
Route::any('/client/password_reset/{id?}/{token?}', 'ApplicationController@view_password_reset');
Route::get('/client/form/destroy', 'ApplicationController@form_destroy');
Route::get('/client/logout', 'ClientController@logout');

/**
* Api
*/
Route::any('/api/{action}/{argument?}', 'ApplicationController@api');
Route::any('/api-administrator/{action}/{argument?}', 'ApplicationController@administratorAPI');
Route::get('/language/{lang}', 'ApplicationController@view_set_lang');
Route::get('/2fa/enable', 'ApplicationController@enableTwoFactor');
Route::get('/2fa/disable', 'ApplicationController@disableTwoFactor');

/**
* Client administration
*/
Route::get('/client', 'ApplicationController@view_client_index');

/** 
* Client administration user logs
*/
Route::get('/client/logs/account', 'ApplicationController@view_client_logs_account');
Route::get('/client/logs/account/clear', 'ApplicationController@view_client_logs_account_clear');
Route::get('/client/logs/account/all', 'ApplicationController@view_client_logs_account_all');

/** 
* Client administration services logs
*/
Route::get('/client/logs/services', 'ApplicationController@view_client_logs_services');

/**
* Client administration services
*/
Route::get('/client/service/{id}', 'ApplicationController@view_client_service');
Route::get('/client/services/view/{draw}/{page?}', 'ApplicationController@view_client_services');
Route::get('/client/services/order', 'ApplicationController@view_client_services_order');
Route::any('/client/services/order/{service}', 'ApplicationController@view_client_services_order');


/**
* Client administration credits
*/
Route::any('/client/credits/send', 'ApplicationController@view_client_credits_send');
Route::any('/client/credits/overview/{limit?}', 'ApplicationController@view_client_credits_overview');
Route::any('/client/credits/purchase', 'ApplicationController@view_client_credits_purchase');

Route::post('/client/credits/purchase/paypal', 'ApplicationController@view_client_credits_purchase_paypal');

Route::get('/client/credits/purchase/paypal/check', 'ApplicationController@view_client_credits_purchase_paypal_check');

/**
* Client administration settings
*/
Route::any('/client/settings', 'ApplicationController@view_client_settings');


/**
* Temporary routes
*/
Route::get('/removeServices', 'ApplicationController@removeServicesTemp');

/**
* Administration
*/
Route::get('/administration', 'ApplicationController@view_administration_index');
Route::get('/administration/clients', 'ApplicationController@view_administration_clients');
Route::get('/administration/blog', 'ApplicationController@view_administration_blog');
Route::get('/administration/blog/all', 'ApplicationController@view_administration_blog');
Route::get('/administration/support', 'ApplicationController@view_administration_support');
Route::any('/administration/support/solve/{id}', 'ApplicationController@view_administration_support_solve');

/**
* Support
*/
Route::get('/support', 'ApplicationController@view_support_index');
Route::any('/support/create', 'ApplicationController@view_support_create');
Route::any('/support/solve/{id}', 'ApplicationController@view_support_solve');