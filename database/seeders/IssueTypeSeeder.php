<?php

namespace Database\Seeders;

use App\Models\IssueType;
use Illuminate\Database\Seeder;

class IssueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IssueType::factory(1)->create(['Name' => 'Hardware']);
        IssueType::factory(1)->create(['Name' => 'Application']);
        IssueType::factory(1)->create(['Name' => 'OS']);
        IssueType::factory(1)->create(['Name' => 'Others']);
    }
}
