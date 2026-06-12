<?php

namespace App\Livewire\Cms;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\MediaItem;
use Livewire\Component;
use Livewire\WithPagination;

class BlogManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $slug = '';

    public ?int $blogCategoryId = null;

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
            $post = BlogPost::with('tags')->findOrFail($id);
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->blogCategoryId = $post->blog_category_id;
            $this->tagsInput = $post->tags->pluck('name')->implode(', ');
            $this->excerpt = $post->excerpt ?? '';
            $this->body = $post->body;
            $this->status = $post->status;

            if ($post->featured_image) {
                $mediaItem = MediaItem::where('path', $post->featured_image)->first();
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
            'blogCategoryId' => 'nullable|exists:blog_categories,id',
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
            : BlogPost::generateSlug($this->title);

        $data = [
            'title' => $this->title,
            'slug' => $slug,
            'blog_category_id' => $this->blogCategoryId,
            'excerpt' => $this->excerpt ?: null,
            'body' => $this->body,
            'status' => $this->status,
            'featured_image' => $imagePath,
            'author_id' => auth()->id(),
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        if ($this->editingId) {
            $post = BlogPost::findOrFail($this->editingId);
            // Preserve the original publish date when re-saving an already-published post.
            if ($post->status === 'published' && $this->status === 'published') {
                unset($data['published_at']);
            }
            $post->update($data);
        } else {
            $post = BlogPost::create($data);
        }

        $post->tags()->sync($this->resolveTagIds());

        $this->closeForm();
        session()->flash('success', 'Post saved successfully.');
    }

    /** @return array<int> */
    private function resolveTagIds(): array
    {
        return collect(explode(',', $this->tagsInput))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique()
            ->map(fn ($name) => BlogTag::findOrCreateByName($name)->id)
            ->values()
            ->all();
    }

    public function publish(int $id): void
    {
        $this->authorize('cms.publish');

        BlogPost::where('id', $id)->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        session()->flash('success', 'Post published.');
    }

    public function unpublish(int $id): void
    {
        $this->authorize('cms.publish');

        BlogPost::where('id', $id)->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        session()->flash('success', 'Post moved to draft.');
    }

    public function delete(int $id): void
    {
        $this->authorize('cms.edit');

        BlogPost::findOrFail($id)->delete();

        session()->flash('success', 'Post deleted.');
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->slug = '';
        $this->blogCategoryId = null;
        $this->tagsInput = '';
        $this->excerpt = '';
        $this->body = '';
        $this->status = 'draft';
        $this->featuredImageId = null;
    }

    public function render()
    {
        $posts = BlogPost::query()
            ->with('category')
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.cms.blog-manager', [
            'posts' => $posts,
            'categories' => BlogCategory::orderBy('sort_order')->orderBy('name')->get(),
        ])->layout('layouts.admin');
    }
}
