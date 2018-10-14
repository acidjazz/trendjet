<?php
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
*/



Route::get('/redirect/{provider}', 'AuthController@redirect')->middleware('web');
Route::get('/callback/{provider}', 'AuthController@callback')->middleware('web');

Route::get('/attempt', 'AuthController@attempt');
Route::get('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout')->middleware('auth:api');
Route::get('/me', 'AuthController@me')->middleware('auth:api');
