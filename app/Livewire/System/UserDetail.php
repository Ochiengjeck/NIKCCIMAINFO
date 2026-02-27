<?php

namespace App\Livewire\System;

use App\Models\Chapter;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserDetail extends Component
{
    public User $user;

    public bool $editingProfile = false;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $jobTitle = '';

    public string $location = '';

    public string $timezone = '';

    public bool $isAdmin = false;

    public string $chapterId = '';

    public string $addRoleName = '';

    public ?string $flashMessage = null;

    public string $flashVariant = 'success';

    public function mount(User $user): void
    {
        $this->authorize('users.view');
        $this->user = $user->load(['roles', 'chapter', 'permissions']);
        $this->fillProfileForm();
    }

    protected function fillProfileForm(): void
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone ?? '';
        $this->jobTitle = $this->user->job_title ?? '';
        $this->location = $this->user->location ?? '';
        $this->timezone = $this->user->timezone ?? '';
        $this->isAdmin = (bool) $this->user->is_admin;
        $this->chapterId = (string) ($this->user->chapter_id ?? '');
    }

    /**
     * Build a grouped map of all permissions with their status for this user:
     * via_role = inherited from an assigned role (read-only)
     * direct   = directly granted on the user (revocable)
     */
    protected function permissionMap(): Collection
    {
        $allPermissions = Permission::orderBy('name')->get();
        $rolePerms = $this->user->getPermissionsViaRoles()->pluck('name');
        $directPerms = $this->user->permissions->pluck('name');

        // Build lookup: permission name → role name that provides it
        $roleSource = [];
        foreach ($this->user->roles as $role) {
            foreach ($role->permissions as $perm) {
                $roleSource[$perm->name] = $role->name;
            }
        }

        return $allPermissions
            ->groupBy(fn ($p) => explode('.', $p->name)[0])
            ->map(fn ($group) => $group->map(fn ($p) => [
                'name' => $p->name,
                'short' => str_replace(explode('.', $p->name)[0].'.', '', $p->name),
                'via_role' => $rolePerms->contains($p->name),
                'direct' => $directPerms->contains($p->name),
                'role_source' => $roleSource[$p->name] ?? null,
            ]));
    }

    public function editProfile(): void
    {
        $this->authorize('users.create');
        $this->editingProfile = true;
    }

    public function saveProfile(): void
    {
        $this->authorize('users.create');

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$this->user->id,
            'phone' => 'nullable|string|max:50',
            'jobTitle' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:100',
            'chapterId' => 'nullable|exists:chapters,id',
        ], [], [
            'jobTitle' => 'job title',
            'chapterId' => 'chapter',
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'job_title' => $this->jobTitle ?: null,
            'location' => $this->location ?: null,
            'timezone' => $this->timezone ?: null,
            'is_admin' => $this->isAdmin,
            'chapter_id' => $this->chapterId ?: null,
        ]);

        $this->user = $this->user->fresh(['roles', 'chapter', 'permissions']);
        $this->fillProfileForm();
        $this->editingProfile = false;
        $this->flashMessage = 'Profile updated successfully.';
        $this->flashVariant = 'success';
    }

    public function cancelProfile(): void
    {
        $this->fillProfileForm();
        $this->resetErrorBag();
        $this->editingProfile = false;
    }

    public function addRole(): void
    {
        $this->authorize('users.assign-role');

        if (! $this->addRoleName) {
            return;
        }

        if ($this->addRoleName === 'super-admin') {
            $this->flashMessage = 'Cannot assign the super-admin role from here.';
            $this->flashVariant = 'warning';

            return;
        }

        $role = Role::where('name', $this->addRoleName)->firstOrFail();
        $this->user->assignRole($role->name);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->user = $this->user->fresh(['roles', 'chapter', 'permissions']);
        $this->addRoleName = '';
        $this->flashMessage = "Role '{$role->name}' assigned. Its permissions are now inherited.";
        $this->flashVariant = 'success';
    }

    public function removeRole(string $roleName): void
    {
        $this->authorize('users.assign-role');

        if ($roleName === 'super-admin') {
            $this->flashMessage = 'Cannot remove the super-admin role from here.';
            $this->flashVariant = 'warning';

            return;
        }

        $this->user->removeRole($roleName);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->user = $this->user->fresh(['roles', 'chapter', 'permissions']);
        $this->flashMessage = "Role '{$roleName}' removed.";
        $this->flashVariant = 'success';
    }

    /**
     * Grant a direct permission by name.
     * Called inline from the permissions panel buttons.
     */
    public function grantPermission(string $name): void
    {
        $this->authorize('users.assign-role');

        if (! $name) {
            return;
        }

        $permission = Permission::where('name', $name)->firstOrFail();
        $this->user->givePermissionTo($permission->name);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->user = $this->user->fresh(['roles', 'chapter', 'permissions']);
        $this->flashMessage = "Permission '{$permission->name}' directly granted.";
        $this->flashVariant = 'success';
    }

    public function revokePermission(string $name): void
    {
        $this->authorize('users.assign-role');

        $this->user->revokePermissionTo($name);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->user = $this->user->fresh(['roles', 'chapter', 'permissions']);
        $this->flashMessage = "Permission '{$name}' revoked.";
        $this->flashVariant = 'success';
    }

    public function render()
    {
        // Re-load relations on every render
        $this->user->load(['roles', 'chapter', 'permissions']);

        $chapters = Chapter::all();

        $assignedRoleNames = $this->user->roles->pluck('name')->toArray();
        $availableRoles = Role::orderBy('name')
            ->get()
            ->filter(fn ($r) => $r->name !== 'super-admin' && ! in_array($r->name, $assignedRoleNames));

        $permissionMap = $this->permissionMap();
        $rolePermCount = $this->user->getPermissionsViaRoles()->count();
        $directPermCount = $this->user->permissions->count();
        $effectiveCount = $this->user->getAllPermissions()->count();

        return view('livewire.system.user-detail', [
            'chapters' => $chapters,
            'availableRoles' => $availableRoles,
            'permissionMap' => $permissionMap,
            'rolePermCount' => $rolePermCount,
            'directPermCount' => $directPermCount,
            'effectiveCount' => $effectiveCount,
        ])->layout('layouts.admin');
    }
}
