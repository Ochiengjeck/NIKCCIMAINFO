<?php

namespace App\Livewire\Finance;

use App\Models\Budget;
use Livewire\Component;
use Livewire\WithPagination;

class BudgetTracker extends Component
{
    use WithPagination;

    public function mount(): void
    {
        $this->authorize('finance.view');
    }

    public function render()
    {
        $budgets = Budget::forChapter()->latest()->paginate(20);

        return view('livewire.finance.budget-tracker', [
            'budgets' => $budgets,
        ])->layout('layouts.admin');
    }
}
