<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Enclosure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enclosures = Enclosure::all();

        foreach ($enclosures as $enclosure){
            // random whether the enclosure contains predators 
            $containsPredators = rand(0, 1) === 1;

            // max animal count for this enclosure
            $animalCount = rand(1, $enclosure->limit);

            // create animals
            if ($containsPredators) {
                Animal::factory()
                    ->count($animalCount)
                    ->predator()
                    ->for($enclosure)
                    ->create();
            } else {
                Animal::factory()
                    ->count($animalCount)
                    ->nonPredator()
                    ->for($enclosure)
                    ->create();
            }
        }

        // create archived animals without enclosures
        Animal::factory()
            ->count(5)
            ->create()
            ->each(function (Animal $animal){
                // this will be soft deleted
                $animal->delete();
            });
    }
}
