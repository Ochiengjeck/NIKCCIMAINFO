<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceReport extends Component
{
    use WithPagination;

    public function mount(): void
    {
        $this->authorize('events.view');
    }

    public function render()
    {
        return view('livewire.events.attendance-report', [
            'events' => Event::forChapter()->withCount(['registrations', 'registrations as checked_in_count' => fn ($q) => $q->whereNotNull('checked_in_at')])->latest()->paginate(20),
        ])->layout('layouts.admin');
    }
}
