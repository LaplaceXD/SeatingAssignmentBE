<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Issue;
use App\Models\User;

class IssuePolicy
{
    public function technician(User $user): bool
    {
        return $user->Role === UserRole::Technician;
    }

    public function admin(User $user): bool
    {
        return $user->isAdmin;
    }

    public function ownerOrAdmin(User $user, Issue $issue): bool
    {
        return $issue->issuer()->is($user) || $this->admin($user);
    }
}
