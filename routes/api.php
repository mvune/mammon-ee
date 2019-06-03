<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {

    Route::get('/user', 'Api\UserAccountController@show');
    Route::put('/user', 'Api\UserAccountController@update');

    Route::get('/charts/boundary-dates', 'Api\ChartDataController@getBoundaryDates');
    Route::get('/charts/balances', 'Api\ChartDataController@getBalances');
    Route::get('/charts/sheet-data', 'Api\ChartDataController@getSheetData');

    Route::get('/transaction-filter-targets', 'Api\TransactionFilterTargetController@index');

    Route::apiResource('accounts', 'Api\AccountController')->except(['show']);

    Route::patch('categories/order', 'Api\CategoryController@order')->name('categories.order');
    Route::apiResource('categories', 'Api\CategoryController')->except(['show']);

    Route::apiResource('transactions', 'Api\TransactionController')->only(['index', 'store']);

});
