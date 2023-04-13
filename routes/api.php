<?php

use App\Http\Controllers\QuizController;
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
    Route::group([
        'prefix' => 'admin',
        'middleware' => 'admin'
    ], function() {
        Route::group(['prefix' => 'courses'], function() {
            Route::get('', [App\Http\Controllers\CourseController::class, 'index'])->name('admin.course.index');
            Route::get('{id}', [App\Http\Controllers\CourseController::class, 'show'])->name('admin.course.show');
            Route::post('', [App\Http\Controllers\CourseController::class, 'store'])->name('admin.course.store');
            Route::put('{id}', [App\Http\Controllers\CourseController::class, 'update'])->name('admin.course.update');
            Route::delete('{id}', [App\Http\Controllers\CourseController::class, 'destroy'])->name('admin.course.destroy');
        });
    
        Route::group(['prefix' => 'course-categories'], function() {
            Route::get('', [App\Http\Controllers\CourseCategoryController::class, 'index'])->name('admin.course-category.index');
            Route::post('', [App\Http\Controllers\CourseCategoryController::class, 'store'])->name('admin.course-category.store');
            Route::put('{id}', [App\Http\Controllers\CourseCategoryController::class, 'update']);
            Route::delete('{id}', [App\Http\Controllers\CourseCategoryController::class, 'destroy'])->name('admin.course-category.destroy');;
        });
    
        Route::group(['prefix' => 'course-sections'], function() {
            Route::get('{courseId}', [App\Http\Controllers\CourseSectionController::class, 'index']);
        });
        
        Route::group(['prefix' => 'articles'], function() {
            Route::get('', [App\Http\Controllers\ArticleController::class, 'index'])->name('admin.article.index');
            Route::get('{id}', [App\Http\Controllers\ArticleController::class, 'show'])->name('admin.article.show');
            Route::post('', [App\Http\Controllers\ArticleController::class, 'store'])->name('admin.article.store');
            Route::put('{id}', [App\Http\Controllers\ArticleController::class, 'update'])->name('admin.article.update');
            Route::delete('{id}', [App\Http\Controllers\ArticleController::class, 'destroy'])->name('admin.article.destroy');
        });
        
        Route::group(['prefix' => 'webinars'], function() {
            Route::get('', [App\Http\Controllers\WebinarController::class, 'index'])->name('admin.webinar.index');
            Route::post('', [App\Http\Controllers\WebinarController::class, 'store'])->name('admin.webinar.store');
            Route::put('{id}', [App\Http\Controllers\WebinarController::class, 'update'])->name('admin.webinar.update');
            Route::delete('{id}', [App\Http\Controllers\WebinarController::class, 'destroy'])->name('admin.webinar.destroy');
        });
    });

    Route::group(['prefix' => 'user'], function() {
        Route::group(['prefix' => 'mycourses'], function() {
            Route::get('', [App\Http\Controllers\UserCourseController::class, 'index'])->name('customer.get.my-course');;
            Route::get('{course}', [App\Http\Controllers\UserCourseController::class, 'show'])->name('customer.show.my-course');;
            Route::post('', [App\Http\Controllers\UserCourseController::class, 'store'])->name('customer.course.my-course');
        });

        Route::group(['prefix' => 'quiz'], function(){
            Route::post('', [QuizController::class, 'saveAnswer'])->name('customer.save.quiz.answer');
        });

        Route::group(['prefix' => 'agendas'], function() {
            Route::get('', [App\Http\Controllers\AgendaController::class, 'index'])->name('customer.agenda.index');
        });

        Route::group(['prefix' => 'courses'], function() {
            Route::get('', [App\Http\Controllers\CourseController::class, 'index']);
        });
    
        Route::group(['prefix' => 'course-categories'], function() {
            Route::get('', [App\Http\Controllers\CourseCategoryController::class, 'index']);
        });
    
        Route::group(['prefix' => 'course-sections'], function() {
            Route::get('{courseId}', [App\Http\Controllers\CourseSectionController::class, 'index']);
        });
        
        Route::group(['prefix' => 'articles'], function() {
            Route::get('', [App\Http\Controllers\ArticleController::class, 'index'])->name('customer.article.index');
            Route::get('{article}', [App\Http\Controllers\ArticleController::class, 'show'])->name('customer.article.show');
        });
        
        Route::group(['prefix' => 'webinars'], function() {
            Route::get('', [App\Http\Controllers\WebinarController::class, 'index']);
            Route::post('join', [App\Http\Controllers\WebinarController::class, 'joinWebinar'])->name('customer.join.webinar');
        });
    });

    Route::post('logout', [App\Http\Controllers\UserController::class, 'logout']);
});

Route::post('register', [App\Http\Controllers\UserController::class, 'register']);
Route::post('login', [App\Http\Controllers\UserController::class, 'login']);





