<?php

namespace App\Livewire\Policy;

use App\Models\TradeStatusReview;
use Livewire\Component;
use Livewire\WithPagination;

class TradeStatusDashboard extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public string $period = '';

    public string $notes = '';

    public string $bilateral_trade_data = '';

    public function mount(): void
    {
        $this->authorize('policy.view');
    }

    public function save(): void
    {
        $this->validate([
            'period' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'bilateral_trade_data' => 'nullable|string',
        ]);

        $data = null;
        if ($this->bilateral_trade_data) {
            $decoded = json_decode($this->bilateral_trade_data, true);
            $data = is_array($decoded) ? $decoded : ['raw' => $this->bilateral_trade_data];
        }

        TradeStatusReview::create([
            'chapter_id' => auth()->user()->chapter_id,
            'period' => $this->period,
            'notes' => $this->notes ?: null,
            'bilateral_trade_data' => $data,
        ]);

        $this->period = '';
        $this->notes = '';
        $this->bilateral_trade_data = '';
        $this->showForm = false;
    }

    public function render()
    {
        $reviews = TradeStatusReview::forChapter()->latest()->paginate(10);

        return view('livewire.policy.trade-status-dashboard', [
            'reviews' => $reviews,
        ])->layout('layouts.admin');
    }
}
