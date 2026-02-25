<?php

namespace App\Livewire\Trade;

use App\Models\Deal;
use Livewire\Component;
use Livewire\WithPagination;

class DealPipeline extends Component
{
    use WithPagination;

    public string $selectedStage = '';

    public function mount(): void
    {
        $this->authorize('trade.view');
    }

    public function setStage(string $stage): void
    {
        $this->selectedStage = $this->selectedStage === $stage ? '' : $stage;
        $this->resetPage();
    }

    public function render()
    {
        $deals = Deal::forChapter()
            ->with(['sector', 'initiator'])
            ->when($this->selectedStage, fn ($q) => $q->where('stage', $this->selectedStage))
            ->latest()
            ->paginate(20);

        return view('livewire.trade.deal-pipeline', [
            'deals' => $deals,
        ])->layout('layouts.admin');
    }
}
