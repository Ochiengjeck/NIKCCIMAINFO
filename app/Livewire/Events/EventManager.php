<?php

namespace App\Livewire\Events;

use App\Models\Chapter;
use App\Models\Event;
use App\Models\MediaItem;
use App\Models\User;
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

    public bool $registration_enabled = false;

    public string $status = 'draft';

    public ?int $chapter_id = null;

    public ?int $organizer_id = null;

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

    /** Event resources — rows of ['title','file_path','file_name','mime_type','size','is_paid','price','currency'] */
    public array $resources = [];

    /** Transient "add one" resource-file picker binding (MediaItem ID) */
    public ?int $resourcePickerId = null;

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
            'registration_enabled' => 'boolean',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'chapter_id' => 'required|exists:chapters,id',
            'organizer_id' => 'required|exists:users,id',
            'featuredImageId' => 'nullable|exists:media_items,id',
            'brochureId' => 'nullable|exists:media_items,id',
            'galleryPickerId' => 'nullable|exists:media_items,id',
            'galleryIds' => 'array',
            'galleryIds.*' => 'exists:media_items,id',
            'inquiryChannels' => 'array',
            'inquiryChannels.*.type' => 'required|in:email,phone,whatsapp,url',
            'inquiryChannels.*.value' => 'required|string|max:255',
            'resourcePickerId' => 'nullable|exists:media_items,id',
            'resources' => 'array',
            'resources.*.title' => 'required|string|max:255',
            'resources.*.file_path' => 'required|string',
            'resources.*.is_paid' => 'boolean',
            'resources.*.price' => 'nullable|numeric|min:0',
            'resources.*.currency' => 'required|in:USD,NGN,KES',
        ];
    }

    public function mount(): void
    {
        $this->authorize('events.view');
        $this->canSelectChapter = auth()->user()->hasRole(['super-admin', 'global-secretariat', 'global-governing-council']);
        $this->chapter_id = auth()->user()->chapter_id;
        $this->organizer_id = auth()->id();
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
        $this->organizer_id = $e->organizer_id;
        $this->title = $e->title;
        $this->type = $e->type;
        $this->description = $e->description ?? '';
        $this->venue = $e->venue ?? '';
        $this->starts_at = $e->starts_at->format('Y-m-d\TH:i');
        $this->ends_at = $e->ends_at->format('Y-m-d\TH:i');
        $this->max_capacity = $e->max_capacity ?? '';
        $this->registration_enabled = (bool) $e->registration_enabled;
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

        $this->resources = $e->resources()->orderBy('sort_order')->get()->map(fn ($r) => [
            'title' => $r->title,
            'file_path' => $r->file_path,
            'file_name' => $r->file_name,
            'mime_type' => $r->mime_type,
            'size' => $r->size,
            'is_paid' => (bool) $r->is_paid,
            'price' => $r->price !== null ? (string) $r->price : '',
            'currency' => $r->currency ?? 'USD',
        ])->all();
        $this->resourcePickerId = null;

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

    public function addResource(): void
    {
        if (! $this->resourcePickerId || ! ($item = MediaItem::find($this->resourcePickerId))) {
            $this->addError('resourcePickerId', 'Choose or upload a file first.');

            return;
        }

        $this->resources[] = [
            'title' => $item->original_filename,
            'file_path' => $item->path,
            'file_name' => $item->original_filename,
            'mime_type' => $item->mime_type,
            'size' => $item->size,
            'is_paid' => false,
            'price' => '',
            'currency' => 'USD',
        ];

        $this->resourcePickerId = null;
    }

    public function removeResource(int $index): void
    {
        unset($this->resources[$index]);
        $this->resources = array_values($this->resources);
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
        $data['organizer_id'] = $this->organizer_id ?: auth()->id();

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
        unset($data['featuredImageId'], $data['brochureId'], $data['galleryPickerId'], $data['galleryIds'], $data['inquiryChannels'], $data['resources'], $data['resourcePickerId']);

        if ($this->editingId) {
            $event = Event::findOrFail($this->editingId);
            $event->update($data);
            session()->flash('success', 'Event updated.');
        } else {
            $event = Event::create($data);
            session()->flash('success', 'Event created.');
        }

        $this->syncResources($event);

        $this->resetForm();
        $this->showForm = false;
    }

    /**
     * Replace the event's resources with the current form rows, resolving each
     * selected MediaItem to its stored path/name.
     */
    private function syncResources(Event $event): void
    {
        $event->resources()->delete();

        foreach ($this->resources as $i => $r) {
            if (empty($r['file_path'])) {
                continue;
            }

            $paid = (bool) ($r['is_paid'] ?? false);

            $event->resources()->create([
                'title' => $r['title'] ?: ($r['file_name'] ?? 'Resource'),
                'file_path' => $r['file_path'],
                'file_name' => $r['file_name'] ?? basename($r['file_path']),
                'mime_type' => $r['mime_type'] ?? null,
                'size' => $r['size'] ?? null,
                'is_paid' => $paid,
                'price' => $paid ? ($r['price'] !== '' ? $r['price'] : null) : null,
                'currency' => $r['currency'] ?? 'USD',
                'sort_order' => $i,
            ]);
        }
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
        $this->organizer_id = auth()->id();
        $this->title = '';
        $this->type = 'flagship';
        $this->description = '';
        $this->venue = '';
        $this->starts_at = '';
        $this->ends_at = '';
        $this->max_capacity = '';
        $this->registration_enabled = false;
        $this->status = 'draft';
        $this->featuredImageId = null;
        $this->brochureId = null;
        $this->galleryIds = [];
        $this->galleryPickerId = null;
        $this->inquiryChannels = [];
        $this->resources = [];
        $this->resourcePickerId = null;
    }

    public function render()
    {
        return view('livewire.events.event-manager', [
            'events' => \App\Models\Event::forChapter()->with('organizer')->latest()->paginate(15),
            'chapters' => $this->canSelectChapter ? Chapter::orderBy('name')->get() : collect(),
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
        ])->layout('layouts.admin');
    }
}
