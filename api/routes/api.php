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

Route::get('/', 'TestController@routes');

Route::apiResource('test', 'TestController');
Route::apiResource('user', 'UserController');

Route::get('/attempt', 'AuthController@attempt');
Route::get('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');
Route::get('/me', 'AuthController@me');

Route::get('/loginas/{email}', 'AuthController@loginAs');

require(base_path('routes/mailable.php'));
