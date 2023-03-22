<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'tree','namespace'=>'App\Http\Controllers'], function () {
    Route::get('','TreeController@render');
    Route::get('node','TreeController@node');
    Route::post('','TreeController@add');
    Route::delete('','TreeController@delete');

});
