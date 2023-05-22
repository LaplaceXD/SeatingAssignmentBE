<?php

namespace Database\Seeders;

use App\Enums\IssueStatus;
use App\Enums\UserRole;
use App\Models\Issue;
use App\Models\IssueType;
use App\Models\Laboratory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class IssueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $students = $users->filter(fn (User $user) => $user->Role === UserRole::Student);
        $professors = $users->filter(fn (User $user) => $user->Role === UserRole::Professor);
        $technicians = $users->filter(fn (User $user) => $user->Role === UserRole::Technician);

        $laboratories = Laboratory::all();
        $issueTypes = IssueType::all();
        $issueTypes->push(null);

        $issues = Issue::factory()
            ->count(35)
            ->sequence(fn (Sequence $sequence) => [
                'IssuerID' => $students->random(1)->first()->UserID,
                'LabID' => $laboratories->random(1)->first()->LabID,
                'TypeID' => $issueTypes->random(1)->first()?->TypeID
            ])
            ->sequence(function (Sequence $sequence) use ($professors, $technicians) {
                $cases = IssueStatus::cases();
                $state = [
                    'Status' => $cases[rand(0, count($cases) - 1)],
                    'ValidatorID' => $professors->random(1)->first()->UserID
                ];
                $coinFlip = rand(0, 1);

                // Accounts for cases wherein issues are stuck in raised (20% chance)
                // or was immediately dropped due to not being replicable (50% chance if status is dropped)
                if (rand(1, 5) === 1 || ($state['Status'] === IssueStatus::Dropped && $coinFlip === 1)) {
                    $state['ValidatorID'] = null;
                    $state['Status'] = null;
                }

                // Accounts for cases that are post validated (75 %), as well as pending cases that do not have
                // an assigned personnel (25 %)
                if ($state['ValidatorID'] !== null && rand(1, 4) > 1) {
                    $state['AssigneeID'] = $technicians->random(1)->first()->UserID;
                }

                return $state;
            })
            ->create();
    }
}
