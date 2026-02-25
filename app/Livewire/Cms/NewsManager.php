<?php

namespace App\Livewire\Cms;

use App\Models\MediaItem;
use App\Models\NewsArticle;
use Livewire\Component;
use Livewire\WithPagination;

class NewsManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $slug = '';

    public string $excerpt = '';

    public string $body = '';

    public string $category = 'news';

    public string $status = 'draft';

    /** MediaItem ID selected via MediaPicker */
    public ?int $featuredImageId = null;

    public string $search = '';

    public string $statusFilter = '';

    public function mount(): void
    {
        $this->authorize('cms.view');
    }

    public function openForm(?int $id = null): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = $id;

        if ($id) {
            $article = NewsArticle::findOrFail($id);
            $this->title = $article->title;
            $this->slug = $article->slug;
            $this->excerpt = $article->excerpt ?? '';
            $this->body = $article->body;
            $this->category = $article->category;
            $this->status = $article->status;

            // Resolve existing image path back to a MediaItem ID if possible
            if ($article->featured_image) {
                $mediaItem = MediaItem::where('path', $article->featured_image)->first();
                $this->featuredImageId = $mediaItem?->id;
            }
        }
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->authorize('cms.edit');

        $this->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'category' => 'required|in:news,press-release,announcement',
            'status' => 'required|in:draft,published',
            'featuredImageId' => 'nullable|exists:media_items,id',
        ]);

        // Resolve image path from MediaItem
        $imagePath = null;
        if ($this->featuredImageId) {
            $imagePath = MediaItem::find($this->featuredImageId)?->path;
        }

        $slug = $this->editingId
            ? $this->slug
            : NewsArticle::generateSlug($this->title);

        $data = [
            'title' => $this->title,
            'slug' => $slug,
            'excerpt' => $this->excerpt ?: null,
            'body' => $this->body,
            'category' => $this->category,
            'status' => $this->status,
            'featured_image' => $imagePath,
            'author_id' => auth()->id(),
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        if ($this->editingId) {
            NewsArticle::where('id', $this->editingId)->update($data);
        } else {
            NewsArticle::create($data);
        }

        $this->closeForm();
        session()->flash('success', 'Article saved successfully.');
    }

    public function publish(int $id): void
    {
        $this->authorize('cms.publish');

        NewsArticle::where('id', $id)->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        session()->flash('success', 'Article published.');
    }

    public function unpublish(int $id): void
    {
        $this->authorize('cms.publish');

        NewsArticle::where('id', $id)->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        session()->flash('success', 'Article moved to draft.');
    }

    public function delete(int $id): void
    {
        $this->authorize('cms.edit');

        $article = NewsArticle::findOrFail($id);
        // Note: we no longer delete the file here — it belongs to the media library
        $article->delete();

        session()->flash('success', 'Article deleted.');
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->slug = '';
        $this->excerpt = '';
        $this->body = '';
        $this->category = 'news';
        $this->status = 'draft';
        $this->featuredImageId = null;
    }

    public function render()
    {
        $articles = NewsArticle::query()
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.cms.news-manager', ['articles' => $articles])
            ->layout('layouts.admin');
    }
}
