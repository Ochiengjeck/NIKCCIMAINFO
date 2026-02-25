<?php

namespace App\Livewire\Kpi;

use App\Models\KpiSnapshot;
use App\Services\KpiCalculationService;
use Livewire\Component;

class ExecutiveDashboard extends Component
{
    public function mount(): void
    {
        $this->authorize('kpi.view');
    }

    public function render(KpiCalculationService $kpiService)
    {
        $liveMetrics = $kpiService->compute(auth()->user()->chapter_id);

        $snapshots = KpiSnapshot::forChapter()
            ->where('period', now()->format('Y-m'))
            ->latest('recorded_at')
            ->get()
            ->groupBy('pillar');

        return view('livewire.kpi.executive-dashboard', [
            'liveMetrics' => $liveMetrics,
            'snapshots' => $snapshots,
        ])->layout('layouts.admin');
    }
}
