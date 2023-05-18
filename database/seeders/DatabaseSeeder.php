<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use App\Enums\UserType;
use App\Models\User;
use App\Models\IssueType;
use App\Models\Laboratory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(3)->create(['Role' => UserType::Student]);
        User::factory(1)->create(['Role' => UserType::Professor]);
        User::factory(1)->create(['Role' => UserType::Technician]);

        Laboratory::factory(4)->create(['LabName' => 'Computer Laboratory']);

        IssueType::factory(1)->create(['Name' => 'Hardware']);
        IssueType::factory(1)->create(['Name' => 'Application']);
        IssueType::factory(1)->create(['Name' => 'OS']);
        IssueType::factory(1)->create(['Name' => 'Others']);
    }
}
