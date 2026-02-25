<?php

namespace App\Livewire\Governance;

use App\Models\BoardResolution;
use Livewire\Component;
use Livewire\WithPagination;

class ResolutionBoard extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public string $title = '';

    public string $resolution_text = '';

    public string $voting_deadline = '';

    protected function rules(): array
    {
        return ['title' => 'required|string|max:255', 'resolution_text' => 'required|string', 'voting_deadline' => 'nullable|date|after:today'];
    }

    public function mount(): void
    {
        $this->authorize('governance.view');
    }

    public function save(): void
    {
        $this->authorize('governance.upload');
        BoardResolution::create(array_merge($this->validate(), ['chapter_id' => auth()->user()->chapter_id, 'moved_by' => auth()->id(), 'status' => 'pending-vote']));
        $this->reset(['title', 'resolution_text', 'voting_deadline']);
        $this->showForm = false;
        session()->flash('success', 'Resolution submitted for vote.');
    }

    public function render()
    {
        return view('livewire.governance.resolution-board', [
            'resolutions' => BoardResolution::forChapter()->with(['movedBy', 'votes'])->latest()->paginate(15),
        ])->layout('layouts.admin');
    }
}
