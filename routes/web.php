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

Route::get('/', 'HomeController@index');
Route::get('/printer', 'PrinterController@index');

Route::get('/printer-list', 'PrinterController@index');

Route::get('/frappe-user', 'FrappeLoginController@index');

Route::get('/test', 'PrinterController@create');
Route::get('/frappe', 'FrappeLoginController@index');
Route::post('/empresa', 'FrappeLoginController@store');
Route::post('/printer-save', 'PrinterController@store');