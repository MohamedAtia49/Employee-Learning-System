<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\SubmitQuizRequest;
use App\Models\Quiz;
use App\Services\Employee\QuizSubmissionService;
use Illuminate\Http\Request;

class QuizSubmissionController extends Controller
{
    public function __construct(
        protected QuizSubmissionService $service
    ) {}

    public function submit(SubmitQuizRequest $request, Quiz $quiz)
    {
        // Submission logic in quiz submission service.
        $submission = $this->service->submit(
            $quiz,
            $request->validated('employee_id'),
            $request->validated('answers')
        );

        if (! $submission) {
            return redirect('/back',with(['no_quiz_submitted' => 'Sorry error found when submit try again !']));
        }

        return view('employee.home',with(['quiz_submitted' => 'Answers submitted successfully.']));
    }
}
