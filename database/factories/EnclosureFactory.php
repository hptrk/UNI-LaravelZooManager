<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enclosure>
 */
class EnclosureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Enclosure ' . ucfirst(fake()->unique()->word()),
            'limit' => fake()->numberBetween(5, 20),
            'feeding_at' => fake()->time('H:i:00')
        ];
    }
}
