<?php

namespace App\Livewire\Membership;

use App\Models\Member;
use Livewire\Component;
use Livewire\WithPagination;

class MemberList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $categoryFilter = '';

    public function mount(): void
    {
        $this->authorize('members.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $members = Member::forChapter()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('membership_number', 'like', "%{$this->search}%")
                    ->orWhere('organization', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->categoryFilter, fn ($q) => $q->where('category_id', $this->categoryFilter))
            ->with(['chapter', 'category'])
            ->latest()
            ->paginate(20);

        return view('livewire.membership.member-list', [
            'members' => $members,
        ])->layout('layouts.admin');
    }
}
