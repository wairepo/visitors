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
    return view('pick');
});

Route::get('/', 'Controller@home');
Route::get('/checkin', 'Controller@home');
Route::post('/submit', 'Controller@submit');

Route::get('/visitors', 'VisitorController@list');
Route::get('/blocks', 'BlockController@list');
Route::get('/logout', 'Controller@logout');
