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

    public function togglePublic(int $id): void
    {
        $this->authorize('documents.approve');

        $document = Document::findOrFail($id);
        $document->update(['is_public' => ! $document->is_public]);
    }

    public function approve(int $id): void
    {
        $this->authorize('documents.approve');

        Document::findOrFail($id)->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        session()->flash('success', 'Document approved.');
    }

    public function archive(int $id): void
    {
        $this->authorize('documents.approve');

        Document::findOrFail($id)->update(['status' => 'archived']);

        session()->flash('success', 'Document archived.');
    }

    public function destroy(int $id): void
    {
        $this->authorize('documents.delete');

        Document::findOrFail($id)->delete();

        session()->flash('success', 'Document deleted.');
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
