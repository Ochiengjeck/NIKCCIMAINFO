<?php

namespace App\Livewire\System;

use App\Models\Chapter;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $selectedUserId = null;

    public string $selectedRole = '';

    public string $selectedChapterId = '';

    public function mount(): void
    {
        $this->authorize('users.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function assignRole(int $userId, string $role): void
    {
        $this->authorize('users.assign-role');

        User::findOrFail($userId)->syncRoles([$role]);

        session()->flash('success', 'Role assigned successfully.');
    }

    public function assignChapter(int $userId, ?int $chapterId): void
    {
        $this->authorize('users.create');

        User::findOrFail($userId)->update(['chapter_id' => $chapterId ?: null]);

        session()->flash('success', 'Chapter assigned successfully.');
    }

    public function toggleActive(int $userId): void
    {
        $this->authorize('users.create');

        $user = User::findOrFail($userId);
        $user->update(['is_admin' => ! $user->is_admin]);

        session()->flash('success', 'User status toggled.');
    }

    public function render()
    {
        $users = User::with(['roles', 'chapter'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(20);

        $chapters = Chapter::all();
        $roles = Role::orderBy('name')->get();

        return view('livewire.system.user-manager', [
            'users' => $users,
            'chapters' => $chapters,
            'roles' => $roles,
        ])->layout('layouts.admin');
    }
}
