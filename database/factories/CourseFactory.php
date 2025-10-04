<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 0, 200),
            'duration' => fake()->numberBetween(1, 100),
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'user_id' => User::inRandomOrder()->where('role', 'instructor')->first()->id,
        ];
    }
}
