<?php

namespace App\Livewire\Membership;

use App\Models\MembershipApplication;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicationList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public function mount(): void
    {
        $this->authorize('members.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $applications = MembershipApplication::forChapter()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('applicant_name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('organization', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->with(['chapter', 'category'])
            ->latest()
            ->paginate(20);

        return view('livewire.membership.application-list', [
            'applications' => $applications,
        ])->layout('layouts.admin');
    }
}
