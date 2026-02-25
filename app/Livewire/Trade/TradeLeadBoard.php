<?php

namespace App\Livewire\Trade;

use App\Models\TradeLead;
use Livewire\Component;
use Livewire\WithPagination;

class TradeLeadBoard extends Component
{
    use WithPagination;

    public string $search = '';

    public string $typeFilter = '';

    public string $statusFilter = '';

    public function mount(): void
    {
        $this->authorize('trade.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
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

        return view('livewire.trade.trade-lead-board', [
            'leads' => $leads,
        ])->layout('layouts.admin');
    }
}
