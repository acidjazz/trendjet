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
use App\Services\YouTubeService;

require(base_path('routes/auth.php'));

Route::get('/', 'TestController@routes');
Route::apiResource('user', 'UserController')->middleware('admin');
Route::get('/loginas/{email}', 'AuthController@loginAs');

Route::get('/youtube/parse', 'YouTubeController@parse')->middleware('auth:api');
Route::get('/youtube/channel/{id}', 'YouTubeController@channel')->middleware('auth:api');

Route::apiResource('plan', 'PlanController');

Route::apiResource('video', 'VideoController')->middleware('auth:api');

require(base_path('routes/mailable.php'));

