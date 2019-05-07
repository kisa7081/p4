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

Route::get('/', 'ConverterController@index');

Route::get('/convert', 'ConverterController@convert');

Route::get('/refresh', 'ConverterController@refresh');

Route::get('/choose', 'ConverterController@choose');

Route::post('/choose', 'ConverterController@saveChoices');

Route::get('/history', 'ConverterController@viewHistory');

Route::put('/history', 'ConverterController@updateHistory');

Route::delete('/history', 'ConverterController@deleteHistory');