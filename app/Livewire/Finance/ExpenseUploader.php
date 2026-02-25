<?php

namespace App\Livewire\Finance;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\MediaItem;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseUploader extends Component
{
    use WithPagination;

    public string $description = '';

    public string $amount = '';

    public string $budget_id = '';

    /** MediaItem ID selected via MediaPicker (optional receipt) */
    public ?int $receiptMediaItemId = null;

    public function mount(): void
    {
        $this->authorize('finance.view');
    }

    public function save(): void
    {
        $this->authorize('finance.create');

        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'budget_id' => 'nullable|exists:budgets,id',
            'receiptMediaItemId' => 'nullable|exists:media_items,id',
        ]);

        $receiptPath = null;
        if ($this->receiptMediaItemId) {
            $receiptPath = MediaItem::find($this->receiptMediaItemId)?->path;
        }

        Expense::create([
            'chapter_id' => auth()->user()->chapter_id,
            'budget_id' => $this->budget_id ?: null,
            'description' => $this->description,
            'amount' => $this->amount,
            'receipt_path' => $receiptPath,
            'submitted_by' => auth()->id(),
        ]);

        $this->description = '';
        $this->amount = '';
        $this->budget_id = '';
        $this->receiptMediaItemId = null;
    }

    public function render()
    {
        $expenses = Expense::forChapter()
            ->where('submitted_by', auth()->id())
            ->with(['budget', 'approver'])
            ->latest()
            ->paginate(20);

        $budgets = Budget::forChapter()->orderBy('fiscal_year')->get();

        return view('livewire.finance.expense-uploader', [
            'expenses' => $expenses,
            'budgets' => $budgets,
        ])->layout('layouts.admin');
    }
}
