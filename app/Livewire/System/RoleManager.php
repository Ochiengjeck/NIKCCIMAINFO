<?php

namespace App\Livewire\System;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManager extends Component
{
    public ?string $flashMessage = null;

    public function mount(): void
    {
        $this->authorize('users.view');
    }

    public function togglePermission(int $roleId, string $permissionName): void
    {
        $this->authorize('users.assign-role');

        $role = Role::findOrFail($roleId);
        if ($role->name === 'super-admin') {
            return; // protected — bypasses all checks via gate-before
        }

        $permission = Permission::where('name', $permissionName)->firstOrFail(); // prevent injection

        if ($role->hasPermissionTo($permission->name)) {
            $role->revokePermissionTo($permission->name);
            $action = 'revoked from';
        } else {
            $role->givePermissionTo($permission->name);
            $action = 'granted to';
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->flashMessage = "Permission '{$permission->name}' {$action} '{$role->name}'.";
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
