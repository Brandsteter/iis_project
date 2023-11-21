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

    Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
});

Route::prefix('/admin')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminUserController::class, 'adminPage']);
        Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'getAllUsers']);

        Route::prefix('/user')->group(function () {
            Route::post('/', [\App\Http\Controllers\AdminUserController::class, 'create']);
            Route::put('/{user}', [\App\Http\Controllers\AdminUserController::class, 'update']);
            Route::delete('/{user}', [\App\Http\Controllers\AdminUserController::class, 'delete']);
        });
    });
});

Route::prefix('/category')->group(function () {
    Route::get('/top', [\App\Http\Controllers\CategoryController::class, 'getCategoriesTopLevelApproved']);
    Route::get('/all', [\App\Http\Controllers\CategoryController::class, 'getCategoriesAll']);

    Route::middleware('auth')->group(function () {
        Route::post('/', [\App\Http\Controllers\CategoryController::class, 'create']);
        Route::get('/', [\App\Http\Controllers\UserController::class, 'categoriesPage']);
        Route::get('/approved', [\App\Http\Controllers\CategoryController::class, 'getCategoriesApproved']);
        Route::get('/unapproved', [\App\Http\Controllers\CategoryController::class, 'getCategoriesUnapproved']);

        Route::middleware('moderator')->group(function () {
            Route::delete('/{category}', [\App\Http\Controllers\CategoryController::class, 'delete']);
            Route::put('/{category}', [\App\Http\Controllers\CategoryController::class, 'update']);
            Route::patch('/{category}', [\App\Http\Controllers\CategoryController::class, 'approve']);
        });
    });

    Route::get('/{category}', [\App\Http\Controllers\CategoryController::class, 'getCategoriesChildrenApproved']);
    Route::get('/{category}/detail', [\App\Http\Controllers\CategoryController::class, 'categoryDetailPage']);
});

Route::prefix('/place')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::post('/', [\App\Http\Controllers\PlaceController::class, 'create']);
        Route::get('/', [\App\Http\Controllers\UserController::class, 'placesPage']);
        Route::get('/approved', [\App\Http\Controllers\PlaceController::class, 'getPlacesApproved']);
        Route::get('/unapproved', [\App\Http\Controllers\PlaceController::class, 'getPlacesUnapproved']);

        Route::middleware('moderator')->group(function () {
            Route::delete('/{place}', [\App\Http\Controllers\PlaceController::class, 'delete']);
            Route::put('/{place}', [\App\Http\Controllers\PlaceController::class, 'update']);
            Route::patch('/{place}', [\App\Http\Controllers\PlaceController::class, 'approve']);
        });
    });
});

Route::prefix('/event')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserController::class, 'eventsPage']);
    Route::get('/approved', [\App\Http\Controllers\EventController::class, 'getEventsApproved']);
    Route::get('/{event}/detail', [\App\Http\Controllers\EventController::class, 'eventDetailPage']);

    Route::middleware('auth')->group(function () {
        Route::post('/', [\App\Http\Controllers\EventController::class, 'create']);
        Route::get('/attended', [\App\Http\Controllers\UserController::class, 'getUserEvents']);
        Route::post('/{event}', [\App\Http\Controllers\UserController::class, 'attend']);

        Route::middleware('moderator')->group(function () {
            Route::get('/unapproved', [\App\Http\Controllers\EventController::class, 'getEventsUnapproved']);
            Route::delete('/{event}', [\App\Http\Controllers\EventController::class, 'delete']);
            Route::put('/{event}', [\App\Http\Controllers\EventController::class, 'update']);
            Route::patch('/{event}', [\App\Http\Controllers\EventController::class, 'approve']);
            Route::post('/{event}/add-category', [\App\Http\Controllers\EventController::class, 'addCategory']);
            Route::post('/{event}/remove-category', [\App\Http\Controllers\EventController::class, 'removeCategory']);
        });

        Route::prefix('/{event}')->group(function () {
            Route::prefix('/comments')->group(function () {
                Route::post('/', [\App\Http\Controllers\CommentController::class, 'create']);
            });
        });
    });
});

Route::prefix('/calendar')->group(function () {
    Route::middleware('auth')->group(function (){
        Route::get('/', [\App\Http\Controllers\UserController::class, 'calendarPage']);
    });
});


