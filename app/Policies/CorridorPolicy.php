<?php

namespace App\Policies;

use App\Models\Corridor;
use App\Models\User;

class CorridorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('corridors.view');
    }

    public function view(User $user, Corridor $corridor): bool
    {
        return $user->can('corridors.view');
    }

    public function create(User $user): bool
    {
        return $user->can('corridors.create');
    }

    public function update(User $user, Corridor $corridor): bool
    {
        return $user->can('corridors.edit');
    }
}
