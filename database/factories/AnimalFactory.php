<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    // species of animals
    private array $predatorSpecies = [
        'Lion', 'Tiger', 'Wolf', 'Bear', 'Lynx', 'Eagle', 'Crocodile', 'Shark'
    ];

    private array $nonPredatorSpecies = [
        'Giraffe', 'Elephant', 'Zebra', 'Rhinoceros', 'Monkey', 'Turtle', 'Parrot', 'Kangaroo'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPredator = fake()->boolean();
        $species = $isPredator
        ? fake()->randomElement($this->predatorSpecies)
        : fake()->randomElement($this->nonPredatorSpecies);

        return [
            'name' => fake()->firstName(),
            'species' => $species,
            'is_predator' => $isPredator,
            'born_at' => fake()->dateTimeBetween('-10 years', 'now'),
            'image_path' => null,
        ];
    }

    // predator animal
    public function predator(): static
    {
        return $this->state(function (array $attributes) {
            $species = fake()->randomElement($this->predatorSpecies);
            
            return [
                'name' => fake()->firstName(),
                'species' => $species,
                'is_predator' => true,
            ];
        });
    }

    // non-predator animal
    public function nonPredator(): static
    {
        return $this->state(function (array $attributes) {
            $species = fake()->randomElement($this->nonPredatorSpecies);
            
            return [
                'name' => fake()->firstName(),
                'species' => $species,
                'is_predator' => false,
            ];
        });
    }
}
