<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'isbn' => rand(10000000000, 99999999999999999),
            'year' => rand(1900, now()->year),
            'title' => fake()->name(),
            'author_id' => Author::inRandomOrder()->first()->id,
            'is_available' => rand(0, 1),
        ];
    }
}
