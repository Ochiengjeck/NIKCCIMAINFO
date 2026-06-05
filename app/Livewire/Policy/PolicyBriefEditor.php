<?php

namespace App\Livewire\Policy;

use App\Concerns\ResolvesActingChapter;
use App\Models\PolicyBrief;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PolicyBriefEditor extends Component
{
    use ResolvesActingChapter, WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $body = '';

    public ?int $file_media_item_id = null;

    public string $status = 'draft';

    public string $reviewer_id = '';

    public function mount(): void
    {
        $this->authorize('policy.view');
    }

    public function openForm(?int $id = null): void
    {
        $this->editingId = $id;

        if ($id) {
            $brief = PolicyBrief::findOrFail($id);
            $this->title = $brief->title;
            $this->body = $brief->body;
            $this->file_media_item_id = $brief->file_media_item_id;
            $this->status = $brief->status;
            $this->reviewer_id = (string) ($brief->reviewer_id ?? '');
        } else {
            $this->title = '';
            $this->body = '';
            $this->file_media_item_id = null;
            $this->status = 'draft';
            $this->reviewer_id = '';
        }

        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'file_media_item_id' => 'nullable|exists:media_items,id',
            'status' => 'required|in:draft,in-review,approved,published',
            'reviewer_id' => 'nullable|exists:users,id',
        ]);

        $data = [
            'title' => $this->title,
            'body' => $this->body,
            'file_media_item_id' => $this->file_media_item_id,
            'status' => $this->status,
            'reviewer_id' => $this->reviewer_id ?: null,
        ];

        if ($this->editingId) {
            PolicyBrief::findOrFail($this->editingId)->update($data);
        } else {
            PolicyBrief::create([
                ...$data,
                'chapter_id' => $this->actingChapterId(),
                'author_id' => auth()->id(),
            ]);
        }

        $this->closeForm();
    }

    public function publish(int $id): void
    {
        $this->authorize('policy.publish');

        PolicyBrief::findOrFail($id)->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublish(int $id): void
    {
        $this->authorize('policy.publish');

        PolicyBrief::findOrFail($id)->update([
            'status' => 'in-review',
            'published_at' => null,
        ]);
    }

    public function destroy(int $id): void
    {
        $this->authorize('policy.delete');

        PolicyBrief::findOrFail($id)->delete();

        session()->flash('success', 'Policy brief deleted.');
    }

    public function render()
    {
        $briefs = PolicyBrief::forChapter()->with(['author', 'reviewer', 'file'])->latest()->paginate(20);
        $reviewers = User::orderBy('name')->get();

        return view('livewire.policy.policy-brief-editor', [
            'briefs' => $briefs,
            'reviewers' => $reviewers,
        ])->layout('layouts.admin');
    }
}
