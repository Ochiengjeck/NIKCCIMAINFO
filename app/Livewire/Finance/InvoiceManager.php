<?php

namespace App\Livewire\Finance;

use App\Models\Invoice;
use App\Models\Member;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $member_id = '';

    public string $due_date = '';

    public string $line_items = '';

    public string $status = 'draft';

    public function mount(): void
    {
        $this->authorize('finance.view');
    }

    public function openForm(?int $id = null): void
    {
        $this->editingId = $id;

        if ($id) {
            $invoice = Invoice::findOrFail($id);
            $this->member_id = (string) ($invoice->member_id ?? '');
            $this->due_date = $invoice->due_date?->format('Y-m-d') ?? '';
            $this->line_items = json_encode($invoice->line_items ?? [], JSON_PRETTY_PRINT);
            $this->status = $invoice->status;
        } else {
            $this->member_id = '';
            $this->due_date = '';
            $this->line_items = '';
            $this->status = 'draft';
        }

        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
    }

    public function save(): void
    {
        $this->authorize('finance.create');

        $this->validate([
            'member_id' => 'nullable|exists:members,id',
            'due_date' => 'required|date',
            'line_items' => 'required|string',
            'status' => 'required|in:draft,sent,paid,overdue',
        ]);

        $items = json_decode($this->line_items, true);
        if (! is_array($items)) {
            $this->addError('line_items', 'Line items must be valid JSON.');

            return;
        }

        $subtotal = collect($items)->sum(fn ($item) => ($item['qty'] ?? 1) * ($item['price'] ?? 0));
        $tax = 0;
        $total = $subtotal + $tax;

        $data = [
            'chapter_id' => auth()->user()->chapter_id,
            'member_id' => $this->member_id ?: null,
            'invoice_number' => $this->editingId ? Invoice::findOrFail($this->editingId)->invoice_number : Invoice::generateInvoiceNumber(),
            'due_date' => $this->due_date,
            'line_items' => $items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => $this->status,
        ];

        if ($this->editingId) {
            Invoice::findOrFail($this->editingId)->update($data);
        } else {
            Invoice::create($data);
        }

        $this->closeForm();
    }

    public function render()
    {
        $invoices = Invoice::forChapter()->with(['member', 'application'])->latest()->paginate(20);
        $members = Member::forChapter()->orderBy('first_name')->get();

        return view('livewire.finance.invoice-manager', [
            'invoices' => $invoices,
            'members' => $members,
        ])->layout('layouts.admin');
    }
}
