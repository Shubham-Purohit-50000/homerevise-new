<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\CourseManagementController;
use App\Http\Controllers\API\QuizController;
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

Route::get('pre-login/details', [CourseManagementController::class, 'prelogin']);

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware(['auth:sanctum'])->group( function () {
    Route::get('name', function(){
        return auth()->user()->email;
    });
    Route::get('user', function(){
        return auth()->user();
    });
    Route::get('user/course/details', [CourseManagementController::class, 'course']);
    Route::post('user/add/course', [CourseManagementController::class, 'addKey']);
    Route::post('user/update/profile', [RegisterController::class, 'updateProfile']);
    Route::get('user/logout', [RegisterController::class, 'logout']);
    Route::get('device/de-register', [RegisterController::class, 'de_register_device']);
    Route::post('user/get/quiz', [QuizController::class, 'getQuiz']);
    Route::post('user/submit/quiz', [QuizController::class, 'submitQuiz']);
    Route::post('user/get/analytics', [QuizController::class, 'getAnalytics']);

});

//for unauthorize user
Route::get('login', function () {
    return response()->json(['message' => 'Unauthorized. Please check your token.'], 401);
})->name('api.login');
