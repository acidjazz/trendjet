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
Route::apiResource('puppet', 'PuppetController')->middleware('admin');
Route::get('/me/stats', 'UserController@stats')->middleware('auth:api');

Route::get('/youtube/parse', 'YouTubeController@parse')->middleware('auth:api');
Route::get('/youtube/channel/{id}', 'YouTubeController@channel')->middleware('auth:api');
Route::get('/youtube/video/{id}', 'YouTubeController@video')->middleware('auth:api');

Route::apiResource('package', 'PackageController');
Route::apiResource('session', 'SessionController')->middleware('auth:api');

Route::apiResource('activity', 'ActivityController')->middleware('auth:api');

Route::apiResource('video', 'VideoController')->middleware('auth:api');
Route::apiResource('purchase', 'PurchaseController')->middleware('auth:api');

Route::get('/boost/meta', 'BoostController@meta');
Route::apiResource('boost', 'BoostController');

Route::apiResource('shot', 'ShotController');

require(base_path('routes/mailable.php'));
