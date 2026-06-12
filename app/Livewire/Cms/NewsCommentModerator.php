<?php

namespace App\Livewire\Cms;

use App\Models\NewsComment;
use Livewire\Component;
use Livewire\WithPagination;

class NewsCommentModerator extends Component
{
    use WithPagination;

    public string $statusFilter = 'pending';

    public function mount(): void
    {
        $this->authorize('cms.view');
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function approve(int $id): void
    {
        $this->authorize('cms.publish');

        NewsComment::where('id', $id)->update(['status' => 'approved']);
        session()->flash('success', 'Comment approved.');
    }

    public function markSpam(int $id): void
    {
        $this->authorize('cms.publish');

        NewsComment::where('id', $id)->update(['status' => 'spam']);
        session()->flash('success', 'Comment marked as spam.');
    }

    public function delete(int $id): void
    {
        $this->authorize('cms.publish');

        NewsComment::findOrFail($id)->delete();
        session()->flash('success', 'Comment deleted.');
    }

    public function render()
    {
        $comments = NewsComment::query()
            ->with('article')
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.cms.news-comment-moderator', [
            'comments' => $comments,
            'pendingCount' => NewsComment::where('status', 'pending')->count(),
        ])->layout('layouts.admin');
    }
}
