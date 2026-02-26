<?php

namespace App\Livewire\Events;

use App\Models\Chapter;
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

    public ?int $chapter_id = null;

    public bool $canSelectChapter = false;

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
            'chapter_id' => 'required|exists:chapters,id',
        ];
    }

    public function mount(): void
    {
        $this->authorize('events.view');
        $this->canSelectChapter = auth()->user()->hasRole(['super-admin', 'global-secretariat', 'global-governing-council']);
        $this->chapter_id = auth()->user()->chapter_id;
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
        $this->chapter_id = $e->chapter_id;
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
        if (! $this->canSelectChapter) {
            $this->chapter_id = auth()->user()->chapter_id;
        }

        if (! $this->chapter_id) {
            $this->addError('chapter_id', 'Please choose a chapter for this event.');
            return;
        }

        $data = $this->validate();
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
        $this->chapter_id = auth()->user()->chapter_id;
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
            'chapters' => $this->canSelectChapter ? Chapter::orderBy('name')->get() : collect(),
        ])->layout('layouts.admin');
    }
}
