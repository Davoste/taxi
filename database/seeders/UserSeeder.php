<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Seed 5 customers
        User::factory(5)->create(['role' => 'customer']);

        // Seed 5 drivers
        User::factory(5)->create(['role' => 'driver']);
    }
}
