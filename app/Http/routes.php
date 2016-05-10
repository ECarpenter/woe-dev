<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('welcome');
    });

});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
    Route::get('/submit', 'WorkOrderController@submit');
    Route::post('/submit-tenant', 'WorkOrderController@save');
});


Route::group(['middleware' => 'web', ['permission:manage-wo']], function () {
	Route::auth();

    //Workorder Routes

	Route::get('/workorders','WorkOrderController@viewlist');
	Route::get('workorders/{workorder}', 'WorkOrderController@show');
    Route::get('workorders/{workorder}/edit', 'WorkOrderController@edit');
    Route::patch('workorders/{workorder}/save', 'WorkOrderController@update');
    Route::get('workorders/{workorder}/bill', 'WorkOrderController@bill');
    Route::patch('workorders/{workorder}/bill', 'WorkOrderController@processbill');
    Route::post('workorders/{workorder}/upload','WorkOrderController@upload');

    //Property Routes
    
    Route::post('property','PropertyController@viewid');
    Route::get('property/list','PropertyController@proplist');
    Route::post('property/save','PropertyController@save');
    Route::get('property/add','PropertyController@add');
    Route::get('property/{property}','PropertyController@show');
    //Tenant Routes
    
    Route::post('tenant','TenantController@viewid');
    Route::get('tenant/list','tenantController@tenantlist');
    Route::get('tenant/add','TenantController@add');
    Route::post('tenant/save','TenantController@save');
    Route::get('tenant/{tenant}','TenantController@show');
    Route::post('tenant/{tenant}/upload','TenantController@upload');

});