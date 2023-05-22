<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeders = [
            UserSeeder::class,
            IssueTypeSeeder::class,
            LaboratorySeeder::class,
            IssueSeeder::class
        ];

        $this->call($seeders);
    }
}
