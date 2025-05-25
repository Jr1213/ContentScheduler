<?php

namespace Database\Factories;

use App\Class\Rules\ImageSizeRule;
use App\Class\Rules\MaxLengthRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rule>
 */
class RuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => MaxLengthRule::class,
            'key' => 'title',
            'value' => 260,
        ];
    }
}
