<?php

namespace App\Livewire\Cms;

use App\Models\MediaItem;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class MediaLibrary extends Component
{
    use WithPagination;

    public string $typeFilter = '';

    public string $search = '';

    public string $message = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorize('cms.view');
    }

    /**
     * Called by the JS uploader via $wire.dispatch('media-uploaded').
     * Refreshes the grid after a new file is added.
     */
    #[On('media-uploaded')]
    public function onMediaUploaded(string $filename = ''): void
    {
        $this->message = $filename ? "'{$filename}' uploaded successfully." : 'File uploaded successfully.';
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $this->authorize('cms.edit');

        $item = MediaItem::findOrFail($id);
        Storage::disk($item->disk)->delete($item->path);
        $item->delete();

        $this->message = 'File deleted.';
        $this->resetPage();
    }

    public function render()
    {
        $items = MediaItem::query()
            ->when($this->typeFilter, fn ($q) => $q->where('type', $this->typeFilter))
            ->when($this->search, fn ($q) => $q->where('original_filename', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(24);

        return view('livewire.cms.media-library', ['items' => $items])
            ->layout('layouts.admin');
    }
}
