<?php

namespace Database\Seeders;

use App\Models\Enclosure;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnclosureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Enclosure::factory()
            ->count(50)
            ->create()
            ->each(function (Enclosure $enclosure) {
                // for every enclosure, attach some users (keepers)
                // 1-3 random users for each enclosure
                $keepers = User::inRandomOrder()->take(rand(1, 3))->get();
                $enclosure->users()->attach($keepers);
            });
    }
}
