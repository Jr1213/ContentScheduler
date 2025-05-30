<?php

namespace Database\Factories;

use App\Models\Platform;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostPlatform>
 */
class PostPlatformFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id' => Post::factory()->create()->id,
            'platform_id' => Platform::factory()->create()->id
        ];
    }
}
