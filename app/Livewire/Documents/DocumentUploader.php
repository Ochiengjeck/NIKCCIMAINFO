<?php

namespace App\Livewire\Documents;

use App\Concerns\ResolvesActingChapter;
use App\Models\Document;
use App\Models\MediaItem;
use Livewire\Component;

class DocumentUploader extends Component
{
    use ResolvesActingChapter;

    public string $title = '';

    public string $category = 'other';

    public string $description = '';

    public bool $is_public = false;

    public int $version = 1;

    /** MediaItem ID selected via MediaPicker */
    public ?int $fileMediaItemId = null;

    public function mount(): void
    {
        $this->authorize('documents.upload');
    }

    /**
     * Storage folder for the current category, resolved with the user's chapter code.
     * Used in the view as a picker prop.
     */
    public function computedFolder(): string
    {
        $code = auth()->user()->chapter?->code ?? 'GLOBAL';

        return "documents/{$code}/{$this->category}";
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:governance,mou,policy-brief,trade-report,audit,meeting-minutes,other',
            'description' => 'nullable|string|max:2000',
            'is_public' => 'boolean',
            'fileMediaItemId' => 'required|exists:media_items,id',
            'version' => 'integer|min:1',
        ]);

        $mediaItem = MediaItem::findOrFail($this->fileMediaItemId);

        Document::create([
            'chapter_id' => $this->actingChapterId(),
            'title' => $this->title,
            'category' => $this->category,
            'description' => $this->description ?: null,
            'is_public' => $this->is_public,
            'file_path' => $mediaItem->path,
            'file_size' => $mediaItem->size,
            'mime_type' => $mediaItem->mime_type,
            'version' => $this->version,
            'uploaded_by' => auth()->id(),
            'status' => 'pending-approval',
        ]);

        $this->title = '';
        $this->category = 'other';
        $this->description = '';
        $this->is_public = false;
        $this->version = 1;
        $this->fileMediaItemId = null;

        session()->flash('success', 'Document uploaded successfully and pending approval.');
    }

    public function render()
    {
        $recentUploads = Document::where('uploaded_by', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.documents.document-uploader', ['recentUploads' => $recentUploads])
            ->layout('layouts.admin');
    }
}
