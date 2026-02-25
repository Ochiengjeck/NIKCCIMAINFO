<?php

namespace App\Policies;

use App\Models\Deal;
use App\Models\User;

class DealPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('trade.view');
    }

    public function view(User $user, Deal $deal): bool
    {
        return $user->can('trade.view');
    }

    public function create(User $user): bool
    {
        return $user->can('trade.create');
    }

    public function update(User $user, Deal $deal): bool
    {
        return $user->can('trade.edit');
    }

    public function delete(User $user, Deal $deal): bool
    {
        return $user->hasRole(['super-admin', 'director-general']);
    }
}
