<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentApproval extends Component
{
    use WithPagination;

    public function mount(): void
    {
        $this->authorize('documents.approve');
    }

    public function approve(int $id): void
    {
        $document = Document::findOrFail($id);

        $document->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        session()->flash('success', "Document '{$document->title}' approved.");
    }

    public function togglePublic(int $id): void
    {
        $document = Document::findOrFail($id);
        $document->update(['is_public' => ! $document->is_public]);
    }

    public function reject(int $id): void
    {
        $document = Document::findOrFail($id);

        $document->update(['status' => 'archived']);

        session()->flash('success', "Document '{$document->title}' rejected and archived.");
    }

    public function render()
    {
        $documents = Document::forChapter()
            ->where('status', 'pending-approval')
            ->with('uploader')
            ->latest()
            ->paginate(20);

        return view('livewire.documents.document-approval', ['documents' => $documents])
            ->layout('layouts.admin');
    }
}
