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

Route::get('/redirect/{provider}', 'AuthController@redirect')->middleware('web');
Route::get('/callback/{provider}', 'AuthController@callback')->middleware('web');

Route::get('/attempt', 'AuthController@attempt');
Route::get('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout')->middleware('auth:api');
Route::get('/me', 'AuthController@me')->middleware('auth:api');
Route::get('/sessions', 'AuthController@sessions')->middleware('auth:api');

Route::get('/loginas/{email}', 'AuthController@loginAs');

require(base_path('routes/mailable.php'));
