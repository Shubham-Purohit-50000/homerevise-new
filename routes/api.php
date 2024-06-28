<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\CourseManagementController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\AnalyticsController;
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
Route::post('store/database/check', function(Request $request) {
    $request->validate([
        'database' => 'required|string',
        'database_url' => 'required|mimes:txt'
    ]);

    if ($request->hasFile('database_url')) {
        // Store the file and get the path
        $filePath = $request->file('database_url')->store('user/database', 'public');

        // Parse the JSON data
        $data = [
            'json' => $request->database,
            'file_path' => $filePath
        ];

        $dat = json_encode($data);

        return $this->sendResponse($data, 'Data Updated Successfully.');
    } else {
        return $this->sendResponse([], 'No file uploaded.', 400);
    }
});

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
    Route::post('user/get/old/quiz', [QuizController::class, 'getOldQuizFormate']);
    Route::post('user/submit/quiz', [QuizController::class, 'submitQuiz']);
    Route::post('user/get/analytics', [QuizController::class, 'getAnalytics']);

    Route::post('user/update-count', [CourseManagementController::class, 'updateCourseCount']);
    Route::post('user/push-app-analytics', [AnalyticsController::class, 'pushAppAnalytics']);
    Route::get('user/get-app-analytics', [AnalyticsController::class, 'getAppAnalytics']);
    Route::post('user/push-played-topics', [AnalyticsController::class, 'pushPlayedTopics']);
    Route::get('user/get-played-topics', [AnalyticsController::class, 'getPlayedTopics']);
    Route::post('user/push-quiz-analytics', [AnalyticsController::class, 'pushQuizAnalytics']);
    Route::get('user/get-quiz-analytics', [AnalyticsController::class, 'getQuizAnalytics']);

    Route::post('user/store/database', [AnalyticsController::class, 'storeDatabase']);
});

Route::get('get/sponsor', [CourseManagementController::class, 'get_banner']);

//for unauthorize user
Route::get('login', function () {
    return response()->json(['message' => 'Unauthorized. Please check your token.'], 401);
})->name('api.login');
