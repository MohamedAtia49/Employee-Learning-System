<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuizSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizAnswer>
 */
class QuizAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_submission_id' => QuizSubmission::factory(),
            'question_id'        => Question::factory(),
            'option_id'          => QuestionOption::factory(),
            'points_earned'      => rand(0, 5),
            'is_correct'         => $this->faker->boolean(70), // 70% Right answer
        ];
    }
}
