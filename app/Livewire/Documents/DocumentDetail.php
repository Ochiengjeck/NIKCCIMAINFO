<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;

class DocumentDetail extends Component
{
    public Document $document;

    public function mount(Document $document): void
    {
        $this->authorize('documents.view');
        $this->document = $document->load(['uploader', 'approver', 'chapter']);
    }

    public function approve(): void
    {
        $this->authorize('documents.approve');

        $this->document->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        $this->document->refresh();

        session()->flash('success', 'Document approved.');
    }

    public function archive(): void
    {
        $this->authorize('documents.approve');

        $this->document->update(['status' => 'archived']);
        $this->document->refresh();

        session()->flash('success', 'Document archived.');
    }

    public function togglePublic(): void
    {
        $this->authorize('documents.approve');

        $this->document->update(['is_public' => ! $this->document->is_public]);
        $this->document->refresh();
    }

    public function destroy()
    {
        $this->authorize('documents.delete');

        $this->document->delete();

        session()->flash('success', 'Document deleted.');

        return $this->redirect(route('admin.documents.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.documents.document-detail')
            ->layout('layouts.admin');
    }
}
