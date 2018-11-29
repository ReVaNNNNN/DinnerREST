<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH Routes
|--------------------------------------------------------------------------
|
 */

/*
 * All Guests
 */
Route::get('fix', 'FixController@fix');
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('reset-password', 'AuthController@resetPassword');

/*
 * Logged Users
 */
Route::group(['middleware' => 'jwt.auth'], function() {
    Route::post('refresh-token', 'AuthController@refresh');
    Route::get('logout', 'AuthController@logout');
});

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
Route::group(['middleware' => 'jwt.auth'], function() {
    /**
     * Dinners Users Routes
     */



    Route::group(['middleware' => 'isAdmin'], function () {
        /*
         * Dinners Admin Routes
         */
        Route::get('dinners', 'DinnerController@index');
        Route::get('dinners/{dinner}', 'DinnerController@show');
        Route::post('dinners', 'DinnerController@store');
        Route::put('dinners/{dinner}', 'DinnerController@update');
        Route::delete('dinners/{dinner}', 'DinnerController@destroy');
    });
});