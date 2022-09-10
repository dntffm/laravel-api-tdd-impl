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

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::group(['prefix' => 'courses'], function() {
        Route::get('', [App\Http\Controllers\CourseController::class, 'index']);
        Route::post('', [App\Http\Controllers\CourseController::class, 'store']);
        Route::put('{id}', [App\Http\Controllers\CourseController::class, 'update']);
        Route::delete('{id}', [App\Http\Controllers\CourseController::class, 'destroy']);
    });

    Route::group(['prefix' => 'mycourses'], function() {
        Route::get('', [App\Http\Controllers\CourseController::class, 'mycourses']);
    });

    Route::group(['prefix' => 'course-categories'], function() {
        Route::get('', [App\Http\Controllers\CourseCategoryController::class, 'index']);
        Route::post('', [App\Http\Controllers\CourseCategoryController::class, 'store']);
        Route::put('{id}', [App\Http\Controllers\CourseCategoryController::class, 'update']);
        Route::delete('{id}', [App\Http\Controllers\CourseCategoryController::class, 'destroy']);
    });

    Route::group(['prefix' => 'articles'], function() {
        Route::get('', [App\Http\Controllers\ArticleController::class, 'index']);
        Route::post('', [App\Http\Controllers\ArticleController::class, 'store']);
        Route::put('{id}', [App\Http\Controllers\ArticleController::class, 'update']);
        Route::delete('{id}', [App\Http\Controllers\ArticleController::class, 'destroy']);
    });
    
    Route::group(['prefix' => 'webinars'], function() {
        Route::get('', [App\Http\Controllers\WebinarController::class, 'index']);
        Route::post('', [App\Http\Controllers\WebinarController::class, 'store']);
        Route::put('{id}', [App\Http\Controllers\WebinarController::class, 'update']);
        Route::delete('{id}', [App\Http\Controllers\WebinarController::class, 'destroy']);
    });

    Route::post('logout', [App\Http\Controllers\UserController::class, 'logout']);
});

Route::post('register', [App\Http\Controllers\UserController::class, 'register']);
Route::post('login', [App\Http\Controllers\UserController::class, 'login']);



