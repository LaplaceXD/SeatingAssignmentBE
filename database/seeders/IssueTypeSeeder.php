<?php

namespace Database\Seeders;

use App\Models\IssueType;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class IssueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Hardware', 'Application', 'OS', 'Peripheral'];

        IssueType::factory()
            ->count(count($types))
            ->sequence(fn (Sequence $sequence) => ['Name' => $types[$sequence->index]])
            ->create();
    }
}
