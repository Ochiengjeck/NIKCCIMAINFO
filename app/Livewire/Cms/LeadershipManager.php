<?php

namespace App\Livewire\Cms;

use App\Models\Chapter;
use App\Models\LeadershipProfile;
use App\Models\MediaItem;
use Livewire\Component;

class LeadershipManager extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $position = '';

    public string $bio = '';

    public string $email = '';

    public ?int $chapterId = null;

    public int $sortOrder = 0;

    public bool $isActive = true;

    /** MediaItem ID selected via MediaPicker */
    public ?int $photoMediaItemId = null;

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
            $profile = LeadershipProfile::findOrFail($id);
            $this->name = $profile->name;
            $this->position = $profile->position;
            $this->bio = $profile->bio ?? '';
            $this->email = $profile->email ?? '';
            $this->chapterId = $profile->chapter_id;
            $this->sortOrder = $profile->sort_order;
            $this->isActive = $profile->is_active;

            // Resolve existing photo path back to a MediaItem ID if possible
            if ($profile->photo_path) {
                $mediaItem = MediaItem::where('path', $profile->photo_path)->first();
                $this->photoMediaItemId = $mediaItem?->id;
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
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'chapterId' => 'nullable|exists:chapters,id',
            'sortOrder' => 'integer|min:0',
            'photoMediaItemId' => 'nullable|exists:media_items,id',
        ]);

        $photoPath = null;
        if ($this->photoMediaItemId) {
            $photoPath = MediaItem::find($this->photoMediaItemId)?->path;
        }

        $data = [
            'name' => $this->name,
            'position' => $this->position,
            'bio' => $this->bio ?: null,
            'email' => $this->email ?: null,
            'chapter_id' => $this->chapterId,
            'sort_order' => $this->sortOrder,
            'is_active' => $this->isActive,
            'photo_path' => $photoPath,
        ];

        if ($this->editingId) {
            LeadershipProfile::where('id', $this->editingId)->update($data);
        } else {
            LeadershipProfile::create($data);
        }

        $this->closeForm();
        session()->flash('success', 'Leadership profile saved.');
    }

    public function delete(int $id): void
    {
        $this->authorize('cms.edit');

        $profile = LeadershipProfile::findOrFail($id);
        // File stays in media library — only remove the profile record
        $profile->delete();

        session()->flash('success', 'Profile deleted.');
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->position = '';
        $this->bio = '';
        $this->email = '';
        $this->chapterId = null;
        $this->sortOrder = 0;
        $this->isActive = true;
        $this->photoMediaItemId = null;
    }

    public function render()
    {
        $profiles = LeadershipProfile::with('chapter')->orderBy('sort_order')->orderBy('name')->get();
        $chapters = Chapter::where('is_active', true)->get();

        return view('livewire.cms.leadership-manager', compact('profiles', 'chapters'))
            ->layout('layouts.admin');
    }
}
