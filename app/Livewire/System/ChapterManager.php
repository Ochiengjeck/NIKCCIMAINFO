<?php

namespace App\Livewire\System;

use App\Models\Chapter;
use Livewire\Component;

class ChapterManager extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $code = '';

    public bool $isActive = true;

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
        $chapter = Chapter::findOrFail($id);
        $this->editingId = $id;
        $this->name = $chapter->name;
        $this->code = $chapter->code;
        $this->isActive = $chapter->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|alpha_num',
        ]);

        $data = [
            'name' => $this->name,
            'code' => strtoupper($this->code),
            'is_active' => $this->isActive,
        ];

        if ($this->editingId) {
            Chapter::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Chapter updated.');
        } else {
            Chapter::create($data);
            session()->flash('success', 'Chapter created.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->code = '';
        $this->isActive = true;
    }

    public function render()
    {
        $chapters = Chapter::all();

        return view('livewire.system.chapter-manager', ['chapters' => $chapters])
            ->layout('layouts.admin');
    }
}
