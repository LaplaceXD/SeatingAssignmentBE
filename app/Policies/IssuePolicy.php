<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\Issue;
use App\Models\User;

class IssuePolicy
{
    public function technician(User $user): bool
    {
        return $user->Role === UserType::Technician;
    }

    public function admin(User $user): bool
    {
        return $user->isAdmin();
    }

    public function ownerOrAdmin(User $user, Issue $issue): bool
    {
        return $user->UserID === $issue->IssuerID || $this->admin($user);
    }
}
