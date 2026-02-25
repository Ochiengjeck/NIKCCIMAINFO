<?php

namespace App\Livewire\Events;

use App\Models\Event;
use App\Models\EventRegistration;
use Livewire\Component;
use Livewire\WithPagination;

class RegistrationDesk extends Component
{
    use WithPagination;

    public ?int $selectedEventId = null;

    public string $search = '';

    public function mount(): void
    {
        $this->authorize('events.manage-attendance');
    }

    public function checkIn(int $registrationId): void
    {
        $reg = EventRegistration::findOrFail($registrationId);
        abort_if($reg->checked_in_at, 422, 'Already checked in.');
        $reg->update(['checked_in_at' => now()]);
        session()->flash('success', "{$reg->attendee_name} checked in.");
    }

    public function render()
    {
        $events = Event::forChapter()->where('status', 'published')->orWhere('status', 'ongoing')->latest()->get();
        $registrations = $this->selectedEventId
            ? EventRegistration::where('event_id', $this->selectedEventId)
                ->when($this->search, fn ($q) => $q->where(fn ($q) => $q->where('attendee_name', 'like', "%{$this->search}%")->orWhere('ticket_number', 'like', "%{$this->search}%")))
                ->with(['ticket'])->paginate(30)
            : collect();

        return view('livewire.events.registration-desk', compact('events', 'registrations'))->layout('layouts.admin');
    }
}
