<?php

namespace App\Livewire\Finance;

use App\Models\FinancialTransaction;
use Livewire\Component;

class RevenueReport extends Component
{
    public function mount(): void
    {
        $this->authorize('finance.view');
    }

    public function render()
    {
        $summary = FinancialTransaction::forChapter()
            ->where('status', 'paid')
            ->selectRaw('type, currency, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('type', 'currency')
            ->get();

        $totalRevenue = $summary->sum('total');

        return view('livewire.finance.revenue-report', [
            'summary' => $summary,
            'totalRevenue' => $totalRevenue,
        ])->layout('layouts.admin');
    }
}
