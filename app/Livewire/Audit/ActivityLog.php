<?php

namespace App\Livewire\Audit;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Component
{
    use WithPagination;

    public string $search = '';

    public string $subjectFilter = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public function mount(): void
    {
        $this->authorize('audit.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSubjectFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $activities = Activity::with(['causer', 'subject'])
            ->when($this->search, fn ($q) => $q->where('description', 'like', "%{$this->search}%"))
            ->when($this->subjectFilter, fn ($q) => $q->where('subject_type', 'like', "%{$this->subjectFilter}%"))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest()
            ->paginate(30);

        return view('livewire.audit.activity-log', ['activities' => $activities])
            ->layout('layouts.admin');
    }
}
