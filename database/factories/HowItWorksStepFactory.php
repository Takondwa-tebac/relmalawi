<?php

namespace Database\Factories;

use App\Models\HowItWorksStep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HowItWorksStep>
 */
class HowItWorksStepFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'body' => fake()->sentence(),
            'detail' => null,
            'icon' => fake()->randomElement(array_keys(HowItWorksStep::ICONS)),
            'sort_order' => fake()->unique()->numberBetween(1, 10000),
            'is_published' => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }
}
