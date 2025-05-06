<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizSubmission>
 */
class QuizSubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id'    => Employee::factory(),
            'quiz_id'       => Quiz::factory(),
            'score'         => rand(0, 100),
            'total_points'  => 100,
            'percentage'    => $this->faker->randomFloat(2, 0, 100),
            'rank'          => rand(1, 50),
            'attempt_number'=> 1,
            'started_at'    => now()->subMinutes(rand(10, 100)),
            'submitted_at'  => now(),
        ];
    }
}
