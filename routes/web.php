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

Route::middleware(['httpauth'])->group(function () {
    Route::get('/', 'Controller@index');
    Route::get('/add_votes', 'Controller@add_votes');
    Route::get('/add_name', 'Controller@add_name');
});