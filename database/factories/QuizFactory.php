<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'title'            => $this->faker->sentence,
            'description'      => $this->faker->paragraph,
            'start_at'       => now()->subDays(rand(1, 10)),
            'end_at'         => now()->addDays(rand(1, 10)),
            'duration_minutes' => rand(10, 120),
            'is_published'     => $this->faker->boolean(90), // 90% Published
            'max_attempts'     => rand(1, 5),
        ];
    }
}
