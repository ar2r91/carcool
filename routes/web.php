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

Route::prefix('competitions')->group(function () {
    Route::get('/', 'CompetitionsController@list');
    Route::get('/{id}', 'CompetitionsController@show');
});

Route::prefix('team')->group(function () {
    Route::get('/', 'TeamsController@list');
    Route::get('/{id}', 'TeamsController@show');
});

Route::prefix('players')->group(function () {
    Route::get('/', 'PlayersController@list');
});
