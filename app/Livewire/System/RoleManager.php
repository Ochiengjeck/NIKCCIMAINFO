<?php

namespace App\Livewire\System;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManager extends Component
{
    public function mount(): void
    {
        $this->authorize('users.view');
    }

    public function render()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();

        $permissions = Permission::all()->groupBy(function ($p) {
            return explode('.', $p->name)[0];
        });

        return view('livewire.system.role-manager', [
            'roles' => $roles,
            'permissions' => $permissions,
        ])->layout('layouts.admin');
    }
}
