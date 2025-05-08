<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        // Eager loading for better performance
        $quiz = $quiz->load(['questions.options', 'user']);

        return view('employee.quizzes.show',compact('quiz'));
    }
}
