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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/mijn-ee/{vue_capture?}', 'DashboardController@index')
    ->where('vue_capture', '[\/\w\.\,\-]*')
    ->name('dashboard')
    ->middleware('auth');

Auth::routes();

Route::delete('/destroy', 'Api\AccountController@destroy');
