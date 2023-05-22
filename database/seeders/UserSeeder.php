<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UserRole::cases() as $role) {
            User::factory()
                ->count(5)
                ->sequence(fn (Sequence $sequence) => [
                    'Email' => $role->value . ($sequence->index === 0 ? '' : $sequence->index) . '@example.com',
                ])
                ->create(['Role' => $role]);
        }
    }
}
