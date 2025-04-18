<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin user for test
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@zoo.com',
            'admin' => true,
            'password' => Hash::make('password'),
        ]);

        // more admins
        User::factory()
            ->count(2)
            ->admin()
            ->create();

        // normal users
        User::factory()
            ->count(5)
            ->create();
    }
}
