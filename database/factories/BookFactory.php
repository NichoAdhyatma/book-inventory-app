<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'description' => fake()->paragraphs(3, true),
            'rating' => fake()->numberBetween(1, 5),
            'thumbnail' => null, // You can add logic to generate fake thumbnails if needed
        ];
    }

    /**
     * Indicate that the book should have a high rating.
     */
    public function highRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->numberBetween(4, 5),
        ]);
    }

    /**
     * Indicate that the book should have a low rating.
     */
    public function lowRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->numberBetween(1, 2),
        ]);
    }

    /**
     * Indicate that the book belongs to a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}