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
                $cases = array_merge(IssueStatus::cases(), [IssueStatus::Dropped]);
                $state = ['Status' => null, 'ValidatorID' => null, 'AssigneeID' => null];

                // There is a 5 / 6 chance that an issue is not stucked on raised 
                if (rand(1, 6) > 1) {
                    $state['Status'] = $cases[rand(0, count($cases) - 1)];

                    // If the Status is dropped there is a 50% chance that it was not validated
                    if ($state['Status'] !== IssueStatus::Dropped || rand(1, 2) === 1) {
                        $state['ValidatorID'] = $professors->random(1)->first()->UserID;
                    }

                    // If issue is validated, it has a 75% chance to have an assignee
                    if ($state['ValidatorID'] !== null && rand(1, 4) > 1) {
                        $state['AssigneeID'] = $technicians->random(1)->first()->UserID;
                    }
                }

                return $state;
            })
            ->create();
    }
}
