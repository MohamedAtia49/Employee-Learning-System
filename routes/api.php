<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CourseController;
use App\Http\Controllers\Api\Admin\QuizController;
use App\Http\Controllers\Api\ProgressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::prefix('v1')->group(function () {

    // Admin Authentication Routes
    Route::prefix('admin')->controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth:api');
    });

    // Protected Routes (require JWT authentication)
    Route::middleware(['jwt_auth'])->group(function () {
        // Course Routes
        Route::apiResource('courses', CourseController::class);
        // Quiz Routes
        Route::apiResource('quizzes', QuizController::class);
        // Progress Tracking For Employee In specific Course
        Route::get('employees/{employeeId}/courses/{courseId}', [ProgressController::class, 'getProgress']);
    });
});

