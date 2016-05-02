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

	Route::get('/workorders','WorkOrderController@viewlist');
	Route::get('workorders/{workorder}', 'WorkOrderController@show');
    Route::get('workorders/{workorder}/edit', 'WorkOrderController@edit');
    Route::patch('workorders/{workorder}/save', 'WorkOrderController@update');
    Route::get('workorders/{workorder}/bill', 'WorkOrderController@bill');
    Route::patch('workorders/{workorder}/bill', 'WorkOrderController@processbill');
});