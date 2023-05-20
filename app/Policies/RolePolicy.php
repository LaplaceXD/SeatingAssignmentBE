<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    public function admin(User $auth): bool
    {
        return $auth->isAdmin();
    }

    public function owner(User $auth, User $user): bool
    {
        return $auth->UserID === $user->UserID;
    }

    public function higherRole(User $auth, User $user): bool
    {
        return $auth->roleLevel() > $user->roleLevel();
    }

    public function ownerOrHigherRole(User $auth, User $user): bool
    {
        return $this->owner($auth, $user) || $this->higherRole($auth, $user);
    }
}
