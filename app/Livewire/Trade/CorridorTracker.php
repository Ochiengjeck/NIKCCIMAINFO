<?php

namespace App\Livewire\Trade;

use App\Models\Corridor;
use Livewire\Component;
use Livewire\WithPagination;

class CorridorTracker extends Component
{
    use WithPagination;

    public ?int $selectedCorridorId = null;

    public string $newNote = '';

    public function mount(): void
    {
        $this->authorize('corridors.view');
    }

    public function selectCorridor(int $id): void
    {
        $this->selectedCorridorId = $this->selectedCorridorId === $id ? null : $id;
        $this->newNote = '';
    }

    public function addLog(): void
    {
        $this->authorize('corridors.edit');

        $this->validate([
            'newNote' => 'required|string|min:3',
        ]);

        $corridor = Corridor::findOrFail($this->selectedCorridorId);
        $corridor->logs()->create([
            'user_id' => auth()->id(),
            'note' => $this->newNote,
            'logged_at' => now(),
        ]);

        $this->newNote = '';
        $this->dispatch('log-added');
    }

    public function render()
    {
        $corridors = Corridor::with(['lead', 'logs.user'])->latest()->paginate(10);

        $selectedCorridor = $this->selectedCorridorId
            ? Corridor::with(['lead', 'logs.user'])->find($this->selectedCorridorId)
            : null;

        return view('livewire.trade.corridor-tracker', [
            'corridors' => $corridors,
            'selectedCorridor' => $selectedCorridor,
        ])->layout('layouts.admin');
    }
}
