<?php

namespace App\Livewire\Kpi;

use App\Models\Chapter;
use App\Models\KpiSnapshot;
use Livewire\Component;

class ChapterComparison extends Component
{
    public function mount(): void
    {
        $this->authorize('kpi.view');
    }

    public function render()
    {
        $chapterIds = Chapter::pluck('id');

        $snapshots = KpiSnapshot::whereIn('chapter_id', $chapterIds)
            ->where('period', now()->format('Y-m'))
            ->get()
            ->groupBy('chapter_id');

        $chapters = Chapter::whereIn('id', $chapterIds)->get();

        return view('livewire.kpi.chapter-comparison', [
            'snapshots' => $snapshots,
            'chapters' => $chapters,
        ])->layout('layouts.admin');
    }
}
