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

use \Torann\GeoIP\Facades\GeoIP;

require(base_path('routes/auth.php'));

Route::get('/', 'TestController@routes');
Route::apiResource('user', 'UserController')->middleware('admin');
Route::get('/loginas/{email}', 'AuthController@loginAs');

// Route::get('/youtube', 'YouTubeController');

require(base_path('routes/mailable.php'));
