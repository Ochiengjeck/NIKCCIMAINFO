<?php

namespace App\Livewire\System;

use App\Models\Chapter;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $roleFilter = '';

    public string $chapterFilter = '';

    // Create user form
    public bool $showCreate = false;

    public string $newName = '';

    public string $newEmail = '';

    public string $newPassword = '';

    public bool $useDefaultPassword = false;

    public string $newRole = '';

    public string $newChapterId = '';

    public bool $newIsAdmin = false;

    public function mount(): void
    {
        $this->authorize('users.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingChapterFilter(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetCreateForm();
        $this->showCreate = true;
    }

    public function createUser(): void
    {
        $this->authorize('users.create');

        $rules = [
            'newName' => 'required|string|max:255',
            'newEmail' => 'required|email|max:255|unique:users,email',
            'newRole' => 'required|exists:roles,name',
            'newChapterId' => 'nullable|exists:chapters,id',
        ];

        if (! $this->useDefaultPassword) {
            $rules['newPassword'] = 'required|string|min:8';
        }

        $this->validate($rules, [], [
            'newName' => 'name',
            'newEmail' => 'email',
            'newPassword' => 'password',
            'newRole' => 'role',
            'newChapterId' => 'chapter',
        ]);

        $password = $this->useDefaultPassword ? 'password' : $this->newPassword;

        $user = User::create([
            'name' => $this->newName,
            'email' => $this->newEmail,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'is_admin' => $this->newIsAdmin,
            'chapter_id' => $this->newChapterId ?: null,
        ]);

        $user->assignRole($this->newRole);

        $roleName = $this->newRole;
        $userName = $user->name;

        $this->resetCreateForm();
        $this->showCreate = false;

        session()->flash('success', "User {$userName} created and assigned role '{$roleName}'.");
    }

    protected function resetCreateForm(): void
    {
        $this->newName = '';
        $this->newEmail = '';
        $this->newPassword = '';
        $this->useDefaultPassword = false;
        $this->newRole = '';
        $this->newChapterId = '';
        $this->newIsAdmin = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $users = User::with(['roles', 'chapter'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->roleFilter, fn ($q) => $q->whereHas('roles', fn ($q) => $q->where('name', $this->roleFilter)))
            ->when($this->chapterFilter, fn ($q) => $q->where('chapter_id', $this->chapterFilter))
            ->latest()
            ->paginate(20);

        $chapters = Chapter::all();
        $roles = Role::orderBy('name')->get();

        $stats = [
            'total' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'recent' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('livewire.system.user-manager', [
            'users' => $users,
            'chapters' => $chapters,
            'roles' => $roles,
            'stats' => $stats,
        ])->layout('layouts.admin');
    }
}
