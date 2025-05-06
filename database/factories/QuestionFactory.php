<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_id'       => Quiz::factory(),
            'question'      => $this->faker->sentence . '?',
            'question_type' => $this->faker->randomElement(['multiple_choice', 'true_false']),
            'points'        => rand(1, 5),
        ];
    }
}
