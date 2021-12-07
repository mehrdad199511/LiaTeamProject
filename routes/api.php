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



/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::namespace('App\Http\Controllers\Api\v1\Auth')
    ->prefix('v1/auth')
    ->group(
        function () {
            Route::post('/login', 'LoginController@login');
            Route::post('/register', 'RegisterController@register');
        }
    );

