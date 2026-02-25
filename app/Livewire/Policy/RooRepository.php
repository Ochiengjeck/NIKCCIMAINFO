<?php

namespace App\Livewire\Policy;

use App\Models\MediaItem;
use App\Models\RooDocument;
use Livewire\Component;
use Livewire\WithPagination;

class RooRepository extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showUpload = false;

    public string $title = '';

    public string $trade_classification = '';

    public string $description = '';

    /** MediaItem ID selected via MediaPicker */
    public ?int $fileMediaItemId = null;

    public function mount(): void
    {
        $this->authorize('policy.view');
    }

    public function save(): void
    {
        $this->authorize('policy.create');

        $this->validate([
            'title' => 'required|string|max:255',
            'trade_classification' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'fileMediaItemId' => 'required|exists:media_items,id',
        ]);

        $path = MediaItem::find($this->fileMediaItemId)->path;

        RooDocument::create([
            'chapter_id' => auth()->user()->chapter_id,
            'title' => $this->title,
            'file_path' => $path,
            'trade_classification' => $this->trade_classification ?: null,
            'description' => $this->description ?: null,
            'uploaded_by' => auth()->id(),
        ]);

        $this->title = '';
        $this->trade_classification = '';
        $this->description = '';
        $this->fileMediaItemId = null;
        $this->showUpload = false;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $documents = RooDocument::forChapter()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('trade_classification', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(20);

        return view('livewire.policy.roo-repository', [
            'documents' => $documents,
        ])->layout('layouts.admin');
    }
}
