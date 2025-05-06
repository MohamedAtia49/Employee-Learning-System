<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizSubmission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2- Create dummy quizzes with specific course_id assignments
        $courseIds = [1, 2];

        foreach ($courseIds as $courseId) {
            Quiz::factory()->create(['course_id' => $courseId])->each(function($quiz) {
                Question::factory(10)->create(['quiz_id' => $quiz->id])
                    ->each(function($question) {
                        QuestionOption::factory(4)->create([
                            'question_id' => $question->id
                        ]);
                    });
            });
        }


        // 5- Create some submissions
        Employee::all()->each(function($employee){
            $quizzes = Quiz::inRandomOrder()->take(3)->get();
            foreach ($quizzes as $quiz) {
                $submission = QuizSubmission::factory()->create([
                    'employee_id' => $employee->id,
                    'quiz_id'    => $quiz->id,
                ]);

                foreach ($quiz->questions as $question) {
                    $option = $question->options->random();

                    QuizAnswer::factory()->create([
                        'quiz_submission_id' => $submission->id,
                        'question_id'        => $question->id,
                        'option_id'          => $option->id,
                        'is_correct'         => $option->is_correct,
                        'points_earned'      => $option->is_correct ? $question->points : 0,
                    ]);
                }
            }
        });
    }
}
