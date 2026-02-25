<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $type = 'flagship';

    public string $description = '';

    public string $venue = '';

    public string $starts_at = '';

    public string $ends_at = '';

    public string $max_capacity = '';

    public string $status = 'draft';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:flagship,trade-mission,sector-forum',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'max_capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
        ];
    }

    public function mount(): void
    {
        $this->authorize('events.view');
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $e = Event::findOrFail($id);
        $this->editingId = $e->id;
        $this->title = $e->title;
        $this->type = $e->type;
        $this->description = $e->description ?? '';
        $this->venue = $e->venue ?? '';
        $this->starts_at = $e->starts_at->format('Y-m-d\TH:i');
        $this->ends_at = $e->ends_at->format('Y-m-d\TH:i');
        $this->max_capacity = $e->max_capacity ?? '';
        $this->status = $e->status;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->authorize('events.create');
        $data = $this->validate();
        $data['chapter_id'] = auth()->user()->chapter_id;
        $data['organizer_id'] = auth()->id();
        if ($this->editingId) {
            Event::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Event updated.');
        } else {
            Event::create($data);
            session()->flash('success', 'Event created.');
        }
        $this->resetForm();
        $this->showForm = false;
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->type = 'flagship';
        $this->description = '';
        $this->venue = '';
        $this->starts_at = '';
        $this->ends_at = '';
        $this->max_capacity = '';
        $this->status = 'draft';
    }

    public function render()
    {
        return view('livewire.events.event-manager', [
            'events' => \App\Models\Event::forChapter()->with('organizer')->latest()->paginate(15),
        ])->layout('layouts.admin');
    }
}
