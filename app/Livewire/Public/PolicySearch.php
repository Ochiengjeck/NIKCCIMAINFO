<?php

namespace App\Livewire\Public;

use App\Models\MediaItem;
use App\Models\PolicyBrief;
use Livewire\Component;
use Livewire\WithPagination;

class PolicySearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $briefs = PolicyBrief::where('status', 'published')
            ->whereNotNull('published_at')
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->latest('published_at')
            ->paginate(12);

        $documents = MediaItem::where('type', 'document')->latest()->limit(20)->get();

        return view('livewire.public.policy-search', compact('briefs', 'documents'))
            ->layout('layouts.website', ['title' => 'Policy & Research — NiKCCIMA']);
    }
}
