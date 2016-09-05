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
Route::group(['middleware' => ['web' ]], function () {

    Route::get('/upload/insurance/{token?}', 'InsuranceController@upload');
    Route::post('/upload/insurance/save', 'InsuranceController@save');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/tenantregister', 'Auth\AuthController@tenantregister');
    Route::get('/tenantregister/city', 'Auth\AuthController@city');
    Route::get('/tenantregister/id', 'Auth\AuthController@id');
    Route::get('/', function () {
        return view('welcome');
    });


});

Route::group(['middleware' => ['web','auth']], function () {
    

    Route::get('home', 'HomeController@index');
    Route::get('submit', 'WorkOrderController@submit');
    Route::post('submit-tenant', 'WorkOrderController@save');
    Route::get('user/changepassword', 'UserController@changePassword');
    Route::patch('user/savepassword', 'UserController@savePassword');
    Route::get('workorders-tenant/{workorder}',  'WorkOrderController@showtenant');

});

//insurance and work orders
Route::group(['middleware' => ['web', 'auth', 'permission:general']], function () {
	



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
    Route::get('tenant/uploadlist', 'TenantController@tenantuploadlist');
    Route::get('tenant/noncompliancelist', 'TenantController@tenantnoncompliancelist');
    Route::get('tenant/add', 'TenantController@add');
    Route::post('tenant/save', 'TenantController@save');
    Route::post('tenant/import', 'TenantController@import');
    Route::post('tenant/refinelist', 'TenantController@refinelist');
    Route::get('tenant/unverifiedlist', 'TenantController@unverifiedlist');
    Route::get('tenant/{tenant}','TenantController@show');
    Route::post('tenant/{tenant}/upload', 'TenantController@upload');
    Route::get('tenant/{tenant}/response', 'TenantController@response');
    Route::get('tenant/{tenant}/notice', 'TenantController@notice');
    Route::post('tenant/{tenant}/update', 'TenantController@update');

    //User Routes
    Route::get('user/add',['middleware' => ['role:admin'], 'uses' => 'UserController@add']);
    Route::post('user/save',['middleware' => ['role:admin'], 'uses' => 'UserController@save']);
    Route::get('user/list',['middleware' => ['role:admin'], 'uses' => 'UserController@userlist']);
    Route::patch('user/verify/update', 'UserController@updateverifyuser');
    Route::get('user/verify/display/{user}', 'UserController@displayverifyuser');
    Route::get('user/{user}', 'UserController@show');

});

//only workorders
Route::group(['middleware' => ['web', 'auth','permission:manage-wo']], function () {

    //Workorder Routes
    Route::get('workorders', 'WorkOrderController@viewlist');
    Route::get('workorders/{workorder}',  'WorkOrderController@show');
    Route::get('workorders/{workorder}/edit',  'WorkOrderController@edit');
    Route::patch('workorders/{workorder}/save',  'WorkOrderController@update');
    Route::get('workorders/{workorder}/bill',  'WorkOrderController@bill');
    Route::patch('workorders/{workorder}/bill',  'WorkOrderController@processbill');
    Route::post('workorders/{workorder}/upload', 'WorkOrderController@upload');

});

//only insurance
Route::group(['middleware' => ['web', 'auth', 'permission:manage-insurance']], function () {

    //Insurance Tracking
    Route::patch('insurance/requirements', 'InsuranceController@savereq');
    Route::patch('insurance/{insurance}/update', 'InsuranceController@update');
    Route::get('insurance/{insurance}/response', 'InsuranceController@response');

});