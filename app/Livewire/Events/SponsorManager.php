<?php

namespace App\Livewire\Events;

use App\Models\Event;
use App\Models\EventSponsor;
use Livewire\Component;
use Livewire\WithPagination;

class SponsorManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $event_id = null;

    public string $sponsor_name = '';

    public string $tier = 'gold';

    public string $amount = '';

    protected function rules(): array
    {
        return ['event_id' => 'required|exists:events,id', 'sponsor_name' => 'required|string|max:255', 'tier' => 'required|in:title,gold,silver,bronze', 'amount' => 'nullable|numeric|min:0'];
    }

    public function mount(): void
    {
        $this->authorize('events.view');
    }

    public function save(): void
    {
        $this->authorize('events.edit');
        EventSponsor::create($this->validate());
        $this->reset(['event_id', 'sponsor_name', 'tier', 'amount']);
        $this->showForm = false;
        session()->flash('success', 'Sponsor added.');
    }

    public function render()
    {
        return view('livewire.events.sponsor-manager', [
            'sponsors' => EventSponsor::with('event')->latest()->paginate(20),
            'events' => Event::forChapter()->latest()->get(),
        ])->layout('layouts.admin');
    }
}
