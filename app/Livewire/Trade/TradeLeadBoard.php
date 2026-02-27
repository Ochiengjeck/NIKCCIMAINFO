<?php

namespace App\Livewire\Trade;

use App\Models\Member;
use App\Models\Sector;
use App\Models\TradeLead;
use Livewire\Component;
use Livewire\WithPagination;

class TradeLeadBoard extends Component
{
    use WithPagination;

    public string $search = '';

    public string $typeFilter = '';

    public string $statusFilter = '';

    public bool $showForm = false;

    public string $title = '';

    public string $description = '';

    public string $sector_id = '';

    public string $member_id = '';

    public string $type = 'export';

    public string $status = 'open';

    public string $contact_info = '';

    public string $expires_at = '';

    public function mount(): void
    {
        $this->authorize('trade.view');
    }

    public function openForm(): void
    {
        $this->authorize('trade.create');
        $this->resetFormFields();
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetFormFields();
    }

    public function save(): void
    {
        $this->authorize('trade.create');

        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'sector_id' => 'required|exists:sectors,id',
            'member_id' => 'nullable|exists:members,id',
            'type' => 'required|in:export,import,partnership',
            'status' => 'required|in:open,matched,closed',
            'contact_info' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date',
        ]);

        TradeLead::create([
            'chapter_id' => auth()->user()->chapter_id,
            'member_id' => $this->member_id ?: null,
            'title' => $this->title,
            'description' => $this->description,
            'sector_id' => $this->sector_id,
            'type' => $this->type,
            'status' => $this->status,
            'contact_info' => $this->contact_info ?: null,
            'expires_at' => $this->expires_at ?: null,
        ]);

        $this->closeForm();
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    private function resetFormFields(): void
    {
        $this->title = '';
        $this->description = '';
        $this->sector_id = '';
        $this->member_id = '';
        $this->type = 'export';
        $this->status = 'open';
        $this->contact_info = '';
        $this->expires_at = '';
    }

    public function render()
    {
        $leads = TradeLead::forChapter()
            ->with(['sector', 'chapter', 'member'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }))
            ->when($this->typeFilter, fn ($q) => $q->where('type', $this->typeFilter))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        $sectors = Sector::where('is_active', true)->orderBy('name')->get();
        $members = Member::forChapter()->orderBy('first_name')->orderBy('last_name')->get();

        return view('livewire.trade.trade-lead-board', [
            'leads' => $leads,
            'sectors' => $sectors,
            'members' => $members,
        ])->layout('layouts.admin');
    }
}
