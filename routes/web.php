<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('app');
});
Route::prefix('/auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', [\App\Http\Controllers\AuthController::class, 'registerPage']);
        Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

        Route::get('/login', [\App\Http\Controllers\AuthController::class, 'loginPage']);
        Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    });

    Route::middleware('auth')->group(function () {
        Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    });
});
Route::prefix('/admin')->group(function () {
    Route::prefix('/user')->group(function () {
        Route::post('/', [\App\Http\Controllers\AdminUserController::class, 'create']);
        Route::put('/{user}', [\App\Http\Controllers\AdminUserController::class, 'update']);
        Route::delete('/{user}', [\App\Http\Controllers\AdminUserController::class, 'delete']);
    });
});
