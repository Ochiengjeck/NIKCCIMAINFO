<?php

namespace App\Livewire\Components;

use App\Models\MediaItem;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Prop;
use Livewire\Component;
use Livewire\WithPagination;

class MediaPicker extends Component
{
    use WithPagination;

    /** The selected MediaItem ID — bindable with wire:model on the parent */
    #[Modelable]
    public ?int $value = null;

    /** Filter the library by disk: 'public' | 'local' | '' (any) */
    #[Prop]
    public string $disk = 'public';

    /** Filter the library by type: 'image' | 'document' | '' (any) */
    #[Prop]
    public string $type = '';

    /** Storage folder hint passed to the upload endpoint */
    #[Prop]
    public string $folder = '';

    /** HTML accept attribute for the file input */
    #[Prop]
    public string $accept = '';

    /** Whether the picker panel is open */
    public bool $open = false;

    /** Active tab: 'upload' | 'library' */
    public string $tab = 'upload';

    /** Library search query */
    public string $search = '';

    /** The currently-selected MediaItem (loaded for preview) */
    public ?MediaItem $selected = null;

    public function mount(): void
    {
        $this->loadSelected();
    }

    public function updatedValue(): void
    {
        $this->loadSelected();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Called from the view when a library item is clicked.
     */
    public function selectItem(int $id): void
    {
        $this->value = $id;
        $this->selected = MediaItem::find($id);
        $this->open = false;
    }

    /**
     * Clear the current selection.
     */
    public function clearSelection(): void
    {
        $this->value = null;
        $this->selected = null;
    }

    /**
     * Called via JS after a successful fetch upload.
     * Sets the value to the newly-created MediaItem ID.
     */
    public function setFromUpload(int $id): void
    {
        $this->value = $id;
        $this->selected = MediaItem::find($id);
        $this->open = false;
        $this->tab = 'upload';
    }

    public function render()
    {
        $items = MediaItem::query()
            ->when($this->disk, fn ($q) => $q->where('disk', $this->disk))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->when($this->search, fn ($q) => $q->where('original_filename', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(12, pageName: 'pickerPage');

        return view('livewire.components.media-picker', ['items' => $items]);
    }

    private function loadSelected(): void
    {
        $this->selected = $this->value ? MediaItem::find($this->value) : null;
    }
}
