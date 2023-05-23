<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Issue;
use App\Models\User;

class IssuePolicy
{
    public function update(User $user, Issue $issue): bool
    {
        /**
         *  Policies
         *  - Students and professors can update until issue gets validated
         * -  Students can only update their own issue
         *  - Technicians can update anytime
         */

        return !$issue->isValidated && ($user->Role === UserRole::Professor
            || $user->Role === UserRole::Student && $issue->issuer()->is($user))
            || $user->Role === UserRole::Technician;
    }

    public function validated(User $user): bool
    {
        /**
         * Policies
         * - Only professors and technicians can validate
         */

        return $user->isAdmin;
    }

    public function updateStatus(User $user): bool
    {
        /**
         * Policies
         * - Only technicians can update status after validation
         */

        return $user->Role === UserRole::Technician;
    }

    public function assign(User $user): bool
    {
        /**
         * Policies
         * - Only technicians can assign
         */

        return $user->Role === UserRole::Technician;
    }

    public function destroy(User $user, Issue $issue): bool
    {
        /**
         * Policies
         * - Professors can drop, but can no longer drop if issue is validated
         * - Technicians can drop anytime
         */

        return $user->Role === UserRole::Professor && !$issue->isValidated
            || $user->Role === UserRole::Technician;
    }
}
