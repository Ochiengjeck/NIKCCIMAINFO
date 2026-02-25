<?php

namespace App\Livewire\Platforms;

use App\Models\TechnicalPlatform;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PlatformManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public string $status = 'active';

    public ?int $coordinatorId = null;

    public function mount(): void
    {
        $this->authorize('settings.view');
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $platform = TechnicalPlatform::findOrFail($id);
        $this->editingId = $id;
        $this->name = $platform->name;
        $this->description = $platform->description ?? '';
        $this->status = $platform->status;
        $this->coordinatorId = $platform->coordinator_id;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description ?: null,
            'status' => $this->status,
            'coordinator_id' => $this->coordinatorId ?: null,
        ];

        if ($this->editingId) {
            TechnicalPlatform::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Platform updated.');
        } else {
            TechnicalPlatform::create($data);
            session()->flash('success', 'Platform created.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        TechnicalPlatform::findOrFail($id)->delete();
        session()->flash('success', 'Platform deleted.');
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->status = 'active';
        $this->coordinatorId = null;
    }

    public function render()
    {
        $platforms = TechnicalPlatform::with('coordinator')->latest()->paginate(10);
        $coordinators = User::orderBy('name')->get();

        return view('livewire.platforms.platform-manager', [
            'platforms' => $platforms,
            'coordinators' => $coordinators,
        ])->layout('layouts.admin');
    }
}
