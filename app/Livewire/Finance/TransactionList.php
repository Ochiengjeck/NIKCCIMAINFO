<?php

namespace App\Livewire\Finance;

use App\Models\FinancialTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $typeFilter = '';

    public string $statusFilter = '';

    public function mount(): void
    {
        $this->authorize('finance.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $transactions = FinancialTransaction::forChapter()
            ->with('member')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('reference', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }))
            ->when($this->typeFilter, fn ($q) => $q->where('type', $this->typeFilter))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.finance.transaction-list', [
            'transactions' => $transactions,
        ])->layout('layouts.admin');
    }
}
