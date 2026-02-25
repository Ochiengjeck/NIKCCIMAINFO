<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentLibrary extends Component
{
    use WithPagination;

    public string $search = '';

    public string $categoryFilter = '';

    public string $statusFilter = '';

    public function mount(): void
    {
        $this->authorize('documents.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $documents = Document::forChapter()
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->categoryFilter, fn ($q) => $q->where('category', $this->categoryFilter))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->with(['uploader', 'approver'])
            ->latest()
            ->paginate(20);

        return view('livewire.documents.document-library', ['documents' => $documents])
            ->layout('layouts.admin');
    }
}
