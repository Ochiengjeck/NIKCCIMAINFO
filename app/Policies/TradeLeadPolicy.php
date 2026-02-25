<?php

namespace App\Policies;

use App\Models\TradeLead;
use App\Models\User;

class TradeLeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('trade.view');
    }

    public function view(User $user, TradeLead $tradeLead): bool
    {
        return $user->can('trade.view');
    }

    public function create(User $user): bool
    {
        return $user->can('trade.create');
    }

    public function update(User $user, TradeLead $tradeLead): bool
    {
        return $user->can('trade.edit');
    }

    public function delete(User $user, TradeLead $tradeLead): bool
    {
        return $user->hasRole(['super-admin', 'director-general']);
    }
}
