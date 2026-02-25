<?php

namespace App\Policies;

use App\Models\MembershipApplication;
use App\Models\User;

class MembershipApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('members.view');
    }

    public function view(User $user, MembershipApplication $application): bool
    {
        return $user->can('members.view');
    }

    public function create(User $user): bool
    {
        return $user->can('members.create');
    }

    public function update(User $user, MembershipApplication $application): bool
    {
        return $user->can('members.edit');
    }

    public function approve(User $user, MembershipApplication $application): bool
    {
        return $user->can('members.approve');
    }

    public function delete(User $user, MembershipApplication $application): bool
    {
        return $user->hasRole(['super-admin', 'director-general']);
    }
}
