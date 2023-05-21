<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(3)->create(['Role' => UserType::Student]);
        User::factory(1)->create(['Email' => 'student@example.com', 'Role' => UserType::Student]);

        User::factory(1)->create(['Role' => UserType::Professor]);
        User::factory(1)->create(['Email' => 'professor@example.com', 'Role' => UserType::Professor]);

        User::factory(1)->create(['Role' => UserType::Technician]);
        User::factory(1)->create(['Email' => 'technician@example.com', 'Role' => UserType::Technician]);
    }
}
