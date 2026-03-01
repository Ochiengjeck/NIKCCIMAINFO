<?php

namespace App\Livewire\Governance;

use App\Models\MediaItem;
use App\Models\StrategicPlan;
use Livewire\Component;
use Livewire\WithPagination;

class StrategicPlanRepository extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public string $title = '';

    public string $fiscal_year = '';

    public string $status = 'draft';

    /** MediaItem ID selected via MediaPicker */
    public ?int $fileMediaItemId = null;

    public function mount(): void
    {
        $this->authorize('governance.view');
    }

    public function save(): void
    {
        $this->authorize('governance.upload');

        $this->validate([
            'title' => 'required|string|max:255',
            'fiscal_year' => 'required|string|max:10',
            'status' => 'required|in:draft,active',
            'fileMediaItemId' => 'required|exists:media_items,id',
        ]);

        $mediaItem = MediaItem::findOrFail($this->fileMediaItemId);

        StrategicPlan::create([
            'title'              => $this->title,
            'fiscal_year'        => $this->fiscal_year,
            'status'             => $this->status,
            'file_path'          => $mediaItem->path,
            'file_media_item_id' => $this->fileMediaItemId,
            'chapter_id'         => auth()->user()->chapter_id,
            'uploaded_by'        => auth()->id(),
        ]);

        $this->reset(['title', 'fiscal_year', 'status', 'fileMediaItemId']);
        $this->status = 'draft';
        $this->showForm = false;

        session()->flash('success', 'Strategic plan uploaded.');
    }

    public function delete(int $planId): void
    {
        $this->authorize('governance.upload');
        StrategicPlan::forChapter()->findOrFail($planId)->delete();
        session()->flash('success', 'Strategic plan removed.');
    }

    public function render()
    {
        return view('livewire.governance.strategic-plan-repository', [
            'plans' => StrategicPlan::forChapter()->with(['uploader', 'mediaItem'])->latest()->paginate(15),
        ])->layout('layouts.admin');
    }
}
