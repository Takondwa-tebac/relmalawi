<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category' => fake()->randomElement(Faq::CATEGORIES),
            'question' => rtrim(fake()->sentence(6), '.').'?',
            'answer' => fake()->paragraph(),
            'sort_order' => fake()->numberBetween(0, 50),
            'is_published' => true,
            'show_on_home' => false,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(['is_published' => false]);
    }

    public function onHome(): static
    {
        return $this->state(['show_on_home' => true]);
    }
}
