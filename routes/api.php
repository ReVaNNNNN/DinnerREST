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

Route::get('dinners', 'DinnerController@index');
Route::get('dinners/{dinner}', 'DinnerController@show');
Route::post('dinners', 'DinnerController@store');
Route::put('dinners/{dinner}', 'DinnerController@update');
Route::delete('dinners/{dinner}', 'DinnerController@destroy');

Route::get('fix', 'FixController@fix');

/*
|--------------------------------------------------------------------------
| AUTH Routes
|--------------------------------------------------------------------------
|
 */

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('refresh-token', 'AuthController@refresh');
Route::post('reset-password', 'AuthController@resetPassword');


Route::group(['middleware' => 'jwt.auth'], function() {
    Route::get('logout', 'AuthController@logout');
});