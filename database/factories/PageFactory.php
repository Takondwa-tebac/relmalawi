<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(2),
            'is_active' => true,
            'show_in_nav' => true,
            'nav_sort' => 0,
            'eyebrow' => fake()->words(3, true),
            'title' => fake()->sentence(2),
            'title_accent' => fake()->sentence(2),
            'description' => fake()->paragraph(),
        ];
    }
}
