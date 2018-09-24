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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'module', 'middleware' => ['auth'] ], function () {
    Route::get('/','Modules\ModuleController@show');
    Route::post('/create','Modules\ModuleController@create');
    Route::post('/update','Modules\ModuleController@update');
    Route::delete('/delete/{id}','Modules\ModuleController@delete');
});
