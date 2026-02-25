<?php

namespace App\Livewire\Cms;

use App\Models\CmsPage;
use Livewire\Component;

class PageEditor extends Component
{
    public string $selectedSlug = '';

    public string $pageTitle = '';

    public array $sections = [];

    public string $metaTitle = '';

    public string $metaDescription = '';

    public bool $isPublished = false;

    public ?CmsPage $page = null;

    public string $pickerTargetField = '';

    public string $pickerSearch = '';

    public function mount(): void
    {
        $this->authorize('cms.view');

        $first = CmsPage::first();
        if ($first) {
            $this->selectPage($first->slug);
        }
    }

    public function selectPage(string $slug): void
    {
        $this->page = CmsPage::where('slug', $slug)->first();

        if (! $this->page) {
            return;
        }

        $this->selectedSlug = $this->page->slug;
        $this->pageTitle = $this->page->title;
        $this->sections = $this->page->sections ?? [];
        $this->metaTitle = $this->page->meta_title ?? '';
        $this->metaDescription = $this->page->meta_description ?? '';
        $this->isPublished = $this->page->is_published;
    }

    public function save(): void
    {
        $this->authorize('cms.edit');

        if (! $this->page) {
            return;
        }

        $this->page->update([
            'sections' => $this->sections,
            'meta_title' => $this->metaTitle ?: null,
            'meta_description' => $this->metaDescription ?: null,
            'updated_by' => auth()->id(),
        ]);

        session()->flash('success', "Page '{$this->page->title}' saved.");
    }

    public function togglePublish(): void
    {
        $this->authorize('cms.publish');

        if (! $this->page) {
            return;
        }

        $this->page->update(['is_published' => ! $this->page->is_published]);
        $this->isPublished = $this->page->is_published;

        $status = $this->isPublished ? 'published' : 'unpublished';
        session()->flash('success', "Page '{$this->page->title}' {$status}.");
    }

    public function openImagePicker(string $field): void
    {
        $this->pickerTargetField = $field;
        $this->pickerSearch = '';
    }

    public function selectImage(string $path): void
    {
        if ($this->pickerTargetField && array_key_exists($this->pickerTargetField, $this->sections)) {
            $this->sections[$this->pickerTargetField] = $path;
        }
        $this->pickerTargetField = '';
        $this->pickerSearch = '';
    }

    public function clearImage(string $field): void
    {
        $this->sections[$field] = '';
    }

    protected function fieldType(string $key): string
    {
        if (str_ends_with($key, '_image') || in_array($key, ['banner_image', 'hero_image'])) {
            return 'image';
        }
        if (preg_match('/responsibilities|programs|kpis|initiatives|_benefits|core_values/', $key)) {
            return 'list';
        }
        if (preg_match('/body|_summary|governance_structure|mandate|description/', $key)) {
            return 'richtext';
        }
        if (str_contains($key, 'address')) {
            return 'address';
        }
        if (str_contains($key, 'phone')) {
            return 'tel';
        }
        if (str_contains($key, 'email')) {
            return 'email';
        }
        if (str_contains($key, '_url') || $key === 'map_embed_url') {
            return 'url';
        }
        if (str_contains($key, '_icon')) {
            return 'icon';
        }

        return 'text';
    }

    public function render()
    {
        $pickerImages = $this->pickerTargetField
            ? \App\Models\MediaItem::where('type', 'image')
                ->when($this->pickerSearch, fn ($q) => $q->where('original_filename', 'like', "%{$this->pickerSearch}%"))
                ->latest()->take(48)->get()
            : collect();

        // Pre-compute field groups to avoid @while in Blade (Livewire's SupportCompiledWireKeys
        // injects $loop->index inside @while, but @while doesn't create $loop — only @foreach does).
        $shortTypes  = ['text', 'tel', 'email', 'icon', 'url'];
        $fieldKeys   = array_keys($this->sections);
        $fieldGroups = [];
        $i           = 0;
        while ($i < count($fieldKeys)) {
            $key  = $fieldKeys[$i];
            $type = $this->fieldType($key);
            if (
                in_array($type, $shortTypes) &&
                isset($fieldKeys[$i + 1]) &&
                in_array($this->fieldType($fieldKeys[$i + 1]), $shortTypes)
            ) {
                $key2 = $fieldKeys[$i + 1];
                $fieldGroups[] = [
                    'layout' => 'pair',
                    'key1'   => $key,  'type1' => $type,
                    'key2'   => $key2, 'type2' => $this->fieldType($key2),
                ];
                $i += 2;
            } else {
                $fieldGroups[] = ['layout' => 'single', 'key' => $key, 'type' => $type];
                $i++;
            }
        }

        return view('livewire.cms.page-editor', [
            'pages'        => CmsPage::orderBy('title')->get(),
            'fieldGroups'  => $fieldGroups,
            'pickerImages' => $pickerImages,
        ])->layout('layouts.admin');
    }
}
