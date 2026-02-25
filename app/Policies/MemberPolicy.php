<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('members.view');
    }

    public function view(User $user, Member $member): bool
    {
        return $user->can('members.view');
    }

    public function create(User $user): bool
    {
        return $user->can('members.create');
    }

    public function update(User $user, Member $member): bool
    {
        return $user->can('members.edit');
    }

    public function export(User $user): bool
    {
        return $user->can('members.export');
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->hasRole(['super-admin', 'director-general']);
    }
}
