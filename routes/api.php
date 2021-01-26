<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'visitors'], function(){
    Route::get('/', 'VisitorController@list');
    Route::post('/new', 'VisitorController@createCheckin');
    Route::put('/edit/{id}', 'VisitorController@checkout');
});

Route::group(['prefix' => 'units'], function(){
    Route::get('/search', 'UnitController@search');
    Route::get('/{id}', 'UnitController@retrieve');
    Route::post('/new', 'UnitController@create');
    Route::put('/edit/{id}', 'UnitController@edit');
    Route::put('/delete/{id}', 'UnitController@delete');
});

Route::group(['prefix' => 'blocks'], function(){
    Route::get('/', 'BlockController@list');
});
