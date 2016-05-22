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


Route::group(['middleware' => ['web', 'permission:manage-wo']], function () {
	

    //Workorder Routes

	Route::get('/workorders', 'WorkOrderController@viewlist');
	Route::get('workorders/{workorder}',  'WorkOrderController@show');
    Route::get('workorders/{workorder}/edit',  'WorkOrderController@edit');
    Route::patch('workorders/{workorder}/save',  'WorkOrderController@update');
    Route::get('workorders/{workorder}/bill',  'WorkOrderController@bill');
    Route::patch('workorders/{workorder}/bill',  'WorkOrderController@processbill');
    Route::post('workorders/{workorder}/upload', 'WorkOrderController@upload');

    //Property Routes
    
    Route::post('property', 'PropertyController@showid');
    Route::get('property/list', 'PropertyController@proplist');
    Route::post('property/save', 'PropertyController@save');
    Route::get('property/add', 'PropertyController@add');
    Route::post('property/import', 'PropertyController@import');
    Route::get('property/{property}', 'PropertyController@show');

    //Group Routes
    Route::post('group', 'GroupController@showid');
    Route::get('group/list', 'GroupController@grouplist');   
    Route::get('group/add', 'GroupController@add');
    Route::post('group/save', 'GroupController@save');
    Route::get('group/{group}', 'GroupController@show');
    Route::get('group/{group}/manage', 'GroupController@manage');
    Route::patch('group/{group}/update', 'GroupController@update');
    Route::patch('group/{group}/remove', 'GroupController@remove');

    //Tenant Routes
    Route::post('tenant', 'TenantController@viewid');
    Route::get('tenant/list', 'TenantController@tenantlist');
    Route::get('tenant/add', 'TenantController@add');
    Route::post('tenant/save', 'TenantController@save');
    Route::get('tenant/{tenant}','TenantController@show');
    Route::post('tenant/{tenant}/upload', 'TenantController@upload');
    Route::get('tenant/{tenant}/edit', 'TenantController@edit');
    Route::post('tenant/{tenant}/update', 'TenantController@update');

    //User Routes
    Route::get('user/add',['middleware' => ['role:admin'], 'uses' => 'UserController@add']);
    Route::post('user/save',['middleware' => ['role:admin'], 'uses' => 'UserController@save']);
    Route::get('user/list',['middleware' => ['role:admin'], 'uses' => 'UserController@userlist']);

    //Insurance Tracking
    Route::patch('insurance/{insurance}/update', 'InsuranceController@update');


});