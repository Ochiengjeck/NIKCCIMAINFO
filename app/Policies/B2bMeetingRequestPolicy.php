<?php

namespace App\Policies;

use App\Models\B2bMeetingRequest;
use App\Models\User;

class B2bMeetingRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('trade.view');
    }

    public function view(User $user, B2bMeetingRequest $b2bMeetingRequest): bool
    {
        return $user->can('trade.view');
    }

    public function create(User $user): bool
    {
        return $user->can('trade.create');
    }
}
