<?php

namespace App\Policies;

use App\Models\Investor;
use App\Models\User;

class InvestorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('trade.view');
    }

    public function view(User $user, Investor $investor): bool
    {
        return $user->can('trade.view');
    }

    public function create(User $user): bool
    {
        return $user->can('trade.create');
    }

    public function update(User $user, Investor $investor): bool
    {
        return $user->can('trade.edit');
    }
}
