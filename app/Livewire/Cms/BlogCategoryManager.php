<?php

namespace App\Livewire\Cms;

use App\Models\BlogCategory;
use Livewire\Component;

class BlogCategoryManager extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public int $sortOrder = 0;

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
            $category = BlogCategory::findOrFail($id);
            $this->name = $category->name;
            $this->description = $category->description ?? '';
            $this->sortOrder = $category->sort_order;
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sortOrder' => 'required|integer|min:0',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => BlogCategory::generateSlug($this->name, $this->editingId),
            'description' => $this->description ?: null,
            'sort_order' => $this->sortOrder,
        ];

        if ($this->editingId) {
            // Keep the existing slug to avoid breaking public URLs.
            unset($data['slug']);
            BlogCategory::where('id', $this->editingId)->update($data);
        } else {
            BlogCategory::create($data);
        }

        $this->closeForm();
        session()->flash('success', 'Category saved.');
    }

    public function delete(int $id): void
    {
        $this->authorize('cms.edit');

        // Posts keep their FK nulled (nullOnDelete) — they become uncategorised.
        BlogCategory::findOrFail($id)->delete();

        session()->flash('success', 'Category deleted.');
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->description = '';
        $this->sortOrder = 0;
    }

    public function render()
    {
        $categories = BlogCategory::withCount('posts')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('livewire.cms.blog-category-manager', ['categories' => $categories])
            ->layout('layouts.admin');
    }
}
