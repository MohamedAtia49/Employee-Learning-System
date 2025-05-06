<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::middleware([EnsureQuizActive::class])->group(function () {
//     Route::get('quizzes/{quiz}', [QuizController::class, 'show']);
//     Route::post('quizzes/{quiz}/submissions', [QuizSubmissionController::class, 'submit']);
// });
