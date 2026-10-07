<?php

namespace Database\Factories;

use App\Enums\FeatureIcon;
use App\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Feature>
 */
class FeatureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'page_slug' => 'about',
            'group' => 'about.pillars',
            'eyebrow' => null,
            'title' => fake()->sentence(3),
            'body' => fake()->paragraph(),
            'icon' => fake()->randomElement(FeatureIcon::cases()),
            'meta' => null,
            'sort_order' => 0,
            'is_published' => true,
        ];
    }

    public function forGroup(string $group): static
    {
        return $this->state([
            'page_slug' => explode('.', $group)[0],
            'group' => $group,
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(['is_published' => false]);
    }
}
