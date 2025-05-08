<?php

namespace App\Services\Employee;
use App\Models\Quiz;
use App\Models\QuizSubmission;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class QuizSubmissionService
{
    public function submit(Quiz $quiz, int $student_id, array $answers): QuizSubmission
    {
        return DB::transaction(function () use ($quiz, $student_id, $answers) {
            // 1- Check if the student has reached the maximum number of attempts
            $this->ensureAttemptsAvailable($quiz, $student_id);

            // 2- Initialize the submission
            $submission = $this->createSubmission($quiz, $student_id);

            // 3- Process the answers
            [$score, $answersPayload] = $this->processAnswers($quiz, $answers);
            $submission->answers()->createMany($answersPayload);

            // 4- Finalize the submission
            $this->finalizeSubmission($submission, $quiz, $score);

            return $submission->refresh();
        });
    }

    protected function ensureAttemptsAvailable(Quiz $quiz, $student_id): void
    {
        $count = $quiz->submissions()->where('student_id', $student_id)->count();
        if ($count >= $quiz->max_attempts) {
            throw new HttpResponseException(response()->json([
                'message' => 'Maximum number of attempts reached.'
            ], 409));
        }
    }

    protected function createSubmission(Quiz $quiz, $student_id): QuizSubmission
    {
        return $quiz->submissions()->create([
            'student_id'     => $student_id,
            'started_at'     => now(),
            'attempt_number' => $quiz->submissions()->count() + 1 ?? 1,
        ]);
    }

    protected function processAnswers(Quiz $quiz, array $answers): array
    {
        $quiz->load('questions.options');

        $score   = 0;
        $payload = [];

        foreach ($answers as $ans) {
            $question = $quiz->questions->firstWhere('id', $ans['question_id']);

            if (! $question) {
                throw new InvalidArgumentException("Question {$ans['question_id']} invalid");
            }

            $option = $question->options->firstWhere('id', $ans['option_id']);

            if (! $option) {
                throw new InvalidArgumentException("Option {$ans['option_id']} invalid for question {$ans['question_id']}");
            }

            $earned = $option->is_correct ? $question->points : 0;
            $score += $earned;

            $payload[] = [
                'question_id'   => $question->id,
                'option_id'     => $option->id,
                'points_earned' => $earned,
                'is_correct'    => $option->is_correct,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        return [$score, $payload];
    }

    protected function finalizeSubmission(QuizSubmission $submission, Quiz $quiz, int $score): void
    {
        $totalPoints = $quiz->questions->sum('points');
        $percentage  = $totalPoints ? round($score / $totalPoints * 100, 2) : 0;

        $average = $quiz->submissions()->avg('score');
        $higher  = $quiz->submissions()->where('score', '>', $score)->count();

        $submission->update([
            'submitted_at'  => now(),
            'score'         => $score,
            'total_points'  => $totalPoints,
            'percentage'    => $percentage,
            'rank'          => $higher + 1,
            'average_score' => round($average, 2),
        ]);
    }
}
