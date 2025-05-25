<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\PostStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'title' => fake()->sentence(),
            'content' => fake()->sentence(16),
            'image_url' => fake()->imageUrl(),
            'scheduled_time' => now()->addDay()->toDateTimeString(),
        ];
    }
}
