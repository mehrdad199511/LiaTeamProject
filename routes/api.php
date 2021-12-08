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

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::namespace('App\Http\Controllers\Api\v1\User')
    ->middleware(['auth:api', 'role'])
    ->prefix('v1/user')
    ->group(
        function() {
            // List of all users
            Route::get('/usersList', 'UserController@users')->middleware(['scope:superAdmin,admin,basic']);
            // Get user Info by id
            Route::get('/getUser/{id}', 'UserController@show')->middleware(['scope:superAdmin,admin,basic']);
            // update user info by id
            Route::put('/update/{id}', 'UserController@update')->middleware(['scope:superAdmin,admin']);
            // update user info by id
            Route::delete('/delete/{id}', 'UserController@destroy')->middleware(['scope:superAdmin']);
        }
    );


/*
|--------------------------------------------------------------------------
| Role Routes
|--------------------------------------------------------------------------
*/

Route::namespace('App\Http\Controllers\Api\v1\Role')
->middleware(['auth:api', 'role'])
->prefix('v1/role')
->group(
    function() {
        // change user role 
        Route::put('/change/{id}', 'RoleController@update')->middleware(['scope:superAdmin']);
    }
);