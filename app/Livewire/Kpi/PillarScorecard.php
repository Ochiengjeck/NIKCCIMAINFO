<?php

namespace App\Livewire\Kpi;

use App\Models\KpiSnapshot;
use Livewire\Component;

class PillarScorecard extends Component
{
    public function mount(): void
    {
        $this->authorize('kpi.view');
    }

    public function render()
    {
        $pillars = KpiSnapshot::forChapter()
            ->where('period', now()->format('Y-m'))
            ->get()
            ->groupBy('pillar');

        return view('livewire.kpi.pillar-scorecard', ['pillars' => $pillars])
            ->layout('layouts.admin');
    }
}
