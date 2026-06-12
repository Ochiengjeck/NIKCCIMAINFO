<?php

namespace App\Livewire\Cms;

use App\Models\MediaItem;
use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\NewsTag;
use Livewire\Component;
use Livewire\WithPagination;

class NewsManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $slug = '';

    public ?int $newsCategoryId = null;

    public string $tagsInput = '';

    public string $excerpt = '';

    public string $body = '';

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
            $article = NewsArticle::with('tags')->findOrFail($id);
            $this->title = $article->title;
            $this->slug = $article->slug;
            $this->newsCategoryId = $article->news_category_id;
            $this->tagsInput = $article->tags->pluck('name')->implode(', ');
            $this->excerpt = $article->excerpt ?? '';
            $this->body = $article->body;
            $this->status = $article->status;

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
            'newsCategoryId' => 'nullable|exists:news_categories,id',
            'tagsInput' => 'nullable|string|max:500',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'status' => 'required|in:draft,published',
            'featuredImageId' => 'nullable|exists:media_items,id',
        ]);

        $imagePath = $this->featuredImageId
            ? MediaItem::find($this->featuredImageId)?->path
            : null;

        $slug = $this->editingId
            ? $this->slug
            : NewsArticle::generateSlug($this->title);

        $data = [
            'title' => $this->title,
            'slug' => $slug,
            'news_category_id' => $this->newsCategoryId,
            'excerpt' => $this->excerpt ?: null,
            'body' => $this->body,
            'status' => $this->status,
            'featured_image' => $imagePath,
            'author_id' => auth()->id(),
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        if ($this->editingId) {
            $article = NewsArticle::findOrFail($this->editingId);
            // Preserve the original publish date when re-saving an already-published article.
            if ($article->status === 'published' && $this->status === 'published') {
                unset($data['published_at']);
            }
            $article->update($data);
        } else {
            $article = NewsArticle::create($data);
        }

        $article->tags()->sync($this->resolveTagIds());

        $this->closeForm();
        session()->flash('success', 'Article saved successfully.');
    }

    /** @return array<int> */
    private function resolveTagIds(): array
    {
        return collect(explode(',', $this->tagsInput))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique()
            ->map(fn ($name) => NewsTag::findOrCreateByName($name)->id)
            ->values()
            ->all();
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

        NewsArticle::findOrFail($id)->delete();

        session()->flash('success', 'Article deleted.');
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->slug = '';
        $this->newsCategoryId = null;
        $this->tagsInput = '';
        $this->excerpt = '';
        $this->body = '';
        $this->status = 'draft';
        $this->featuredImageId = null;
    }

    public function render()
    {
        $articles = NewsArticle::query()
            ->with('category')
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.cms.news-manager', [
            'articles' => $articles,
            'categories' => NewsCategory::orderBy('sort_order')->orderBy('name')->get(),
        ])->layout('layouts.admin');
    }
}
