<?php

namespace App\Livewire\Cms;

use App\Models\ContactInquiry;
use Livewire\Component;
use Livewire\WithPagination;

class ContactManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $chapterFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function markRead(int $id): void
    {
        $this->authorize('cms.view');
        ContactInquiry::findOrFail($id)->update(['status' => 'read']);
        session()->flash('success', 'Marked as read.');
    }

    public function markResponded(int $id): void
    {
        $this->authorize('cms.view');
        ContactInquiry::findOrFail($id)->update(['status' => 'responded']);
        session()->flash('success', 'Marked as responded.');
    }

    public function delete(int $id): void
    {
        $this->authorize('cms.edit');
        ContactInquiry::findOrFail($id)->delete();
        session()->flash('success', 'Inquiry deleted.');
    }

    public function render()
    {
        $this->authorize('cms.view');

        $inquiries = ContactInquiry::query()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('subject', 'like', '%'.$this->search.'%');
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->chapterFilter, fn ($q) => $q->where('chapter', $this->chapterFilter))
            ->latest()
            ->paginate(20);

        $stats = [
            'total'     => ContactInquiry::count(),
            'new'       => ContactInquiry::where('status', 'new')->count(),
            'responded' => ContactInquiry::where('status', 'responded')->count(),
        ];

        return view('livewire.cms.contact-manager', compact('inquiries', 'stats'))
            ->layout('layouts.admin');
    }
}
