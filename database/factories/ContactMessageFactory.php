<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'message' => fake()->paragraph(),
            'read_at' => null,
            'ip_address' => fake()->ipv4(),
        ];
    }

    public function read(): static
    {
        return $this->state(fn () => ['read_at' => now()]);
    }
}
