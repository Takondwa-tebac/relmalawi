<?php

namespace Database\Factories;

use App\Models\Stat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Stat>
 */
class StatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'value' => (string) fake()->numberBetween(1, 99),
            'label' => fake()->words(2, true),
            'sort_order' => 0,
        ];
    }
}
