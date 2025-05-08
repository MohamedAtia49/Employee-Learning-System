<?php

use App\Http\Controllers\Employee\CertificationController;
use App\Http\Controllers\Employee\CourseController;
use App\Http\Controllers\Employee\QuizController;
use App\Http\Controllers\Employee\QuizSubmissionController;
use App\Http\Middleware\EnsureQuizActive;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/employee/login', function () {
    return view('employee.auth.login');
})->name('employee.login')->middleware('guest:employee');


Route::middleware(['check_employee'])->prefix('employee')->group(function () {

    Auth::routes(['register' => false]);

    // Employee Home
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // Show all available courses
    Route::get('/courses',[CourseController::class,'courses']);
    // Get my courses
    Route::get('/my-courses',[CourseController::class,'myCourses']);
    // Get my available quizzes
    Route::middleware([EnsureQuizActive::class])->group(function () {
        Route::get('quizzes/{quiz}', [QuizController::class, 'show']);
        Route::post('quizzes/{quiz}/submissions', [QuizSubmissionController::class, 'submit']);
    });
    // Show my certificates
    Route::get('/certifications/show', [CertificationController::class, 'show']);

});

