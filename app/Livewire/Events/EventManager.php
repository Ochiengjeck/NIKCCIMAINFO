<?php

namespace App\Livewire\Events;

use App\Models\Chapter;
use App\Models\Event;
use App\Models\MediaItem;
use Livewire\Component;
use Livewire\WithPagination;

class EventManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $type = 'flagship';

    public string $description = '';

    public string $venue = '';

    public string $starts_at = '';

    public string $ends_at = '';

    public string $max_capacity = '';

    public string $status = 'draft';

    public ?int $chapter_id = null;

    public bool $canSelectChapter = false;

    /** Poster / featured image MediaItem ID (via MediaPicker) */
    public ?int $featuredImageId = null;

    /** Downloadable brochure (PDF) MediaItem ID (via MediaPicker) */
    public ?int $brochureId = null;

    /** Gallery image MediaItem IDs */
    public array $galleryIds = [];

    /** Transient "add one" gallery picker binding */
    public ?int $galleryPickerId = null;

    /** Inquiry channels — list of ['type' => email|phone|whatsapp|url, 'value' => '...'] */
    public array $inquiryChannels = [];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:flagship,trade-mission,sector-forum',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'max_capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'chapter_id' => 'required|exists:chapters,id',
            'featuredImageId' => 'nullable|exists:media_items,id',
            'brochureId' => 'nullable|exists:media_items,id',
            'galleryPickerId' => 'nullable|exists:media_items,id',
            'galleryIds' => 'array',
            'galleryIds.*' => 'exists:media_items,id',
            'inquiryChannels' => 'array',
            'inquiryChannels.*.type' => 'required|in:email,phone,whatsapp,url',
            'inquiryChannels.*.value' => 'required|string|max:255',
        ];
    }

    public function mount(): void
    {
        $this->authorize('events.view');
        $this->canSelectChapter = auth()->user()->hasRole(['super-admin', 'global-secretariat', 'global-governing-council']);
        $this->chapter_id = auth()->user()->chapter_id;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $e = Event::findOrFail($id);
        $this->editingId = $e->id;
        $this->chapter_id = $e->chapter_id;
        $this->title = $e->title;
        $this->type = $e->type;
        $this->description = $e->description ?? '';
        $this->venue = $e->venue ?? '';
        $this->starts_at = $e->starts_at->format('Y-m-d\TH:i');
        $this->ends_at = $e->ends_at->format('Y-m-d\TH:i');
        $this->max_capacity = $e->max_capacity ?? '';
        $this->status = $e->status;

        // Resolve stored paths back to MediaItem IDs for the pickers
        $this->featuredImageId = $e->featured_image
            ? MediaItem::where('path', $e->featured_image)->value('id')
            : null;
        $this->brochureId = $e->brochure_path
            ? MediaItem::where('path', $e->brochure_path)->value('id')
            : null;
        $this->galleryIds = MediaItem::whereIn('path', $e->gallery ?? [])
            ->pluck('id')
            ->all();
        $this->galleryPickerId = null;
        $this->inquiryChannels = $e->inquiry_channels ?? [];

        $this->showForm = true;
    }

    public function addInquiryChannel(): void
    {
        $this->inquiryChannels[] = ['type' => 'email', 'value' => ''];
    }

    public function removeInquiryChannel(int $index): void
    {
        unset($this->inquiryChannels[$index]);
        $this->inquiryChannels = array_values($this->inquiryChannels);
    }

    public function addGalleryImage(): void
    {
        if ($this->galleryPickerId && ! in_array($this->galleryPickerId, $this->galleryIds, true)) {
            $this->galleryIds[] = $this->galleryPickerId;
        }
        $this->galleryPickerId = null;
    }

    public function removeGalleryImage(int $id): void
    {
        $this->galleryIds = array_values(array_filter(
            $this->galleryIds,
            fn ($existing) => $existing !== $id
        ));
    }

    public function save(): void
    {
        $this->authorize('events.create');
        if (! $this->canSelectChapter) {
            $this->chapter_id = auth()->user()->chapter_id;
        }

        if (! $this->chapter_id) {
            $this->addError('chapter_id', 'Please choose a chapter for this event.');

            return;
        }

        $data = $this->validate();
        $data['organizer_id'] = auth()->id();

        // Resolve picker selections (MediaItem IDs) to stored paths
        $data['featured_image'] = $this->featuredImageId
            ? MediaItem::find($this->featuredImageId)?->path
            : null;

        $data['gallery'] = $this->galleryIds
            ? MediaItem::whereIn('id', $this->galleryIds)->pluck('path')->all()
            : [];

        if ($this->brochureId && $brochure = MediaItem::find($this->brochureId)) {
            $data['brochure_path'] = $brochure->path;
            $data['brochure_name'] = $brochure->original_filename;
        } else {
            $data['brochure_path'] = null;
            $data['brochure_name'] = null;
        }

        // Normalise inquiry channels — drop blanks, trim values
        $data['inquiry_channels'] = collect($this->inquiryChannels)
            ->map(fn ($c) => ['type' => $c['type'] ?? '', 'value' => trim($c['value'] ?? '')])
            ->filter(fn ($c) => $c['type'] !== '' && $c['value'] !== '')
            ->values()
            ->all();

        // Not real columns — strip the transient/picker-only validation keys
        unset($data['featuredImageId'], $data['brochureId'], $data['galleryPickerId'], $data['galleryIds'], $data['inquiryChannels']);

        if ($this->editingId) {
            Event::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Event updated.');
        } else {
            Event::create($data);
            session()->flash('success', 'Event created.');
        }
        $this->resetForm();
        $this->showForm = false;
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->chapter_id = auth()->user()->chapter_id;
        $this->title = '';
        $this->type = 'flagship';
        $this->description = '';
        $this->venue = '';
        $this->starts_at = '';
        $this->ends_at = '';
        $this->max_capacity = '';
        $this->status = 'draft';
        $this->featuredImageId = null;
        $this->brochureId = null;
        $this->galleryIds = [];
        $this->galleryPickerId = null;
        $this->inquiryChannels = [];
    }

    public function render()
    {
        return view('livewire.events.event-manager', [
            'events' => \App\Models\Event::forChapter()->with('organizer')->latest()->paginate(15),
            'chapters' => $this->canSelectChapter ? Chapter::orderBy('name')->get() : collect(),
        ])->layout('layouts.admin');
    }
}
