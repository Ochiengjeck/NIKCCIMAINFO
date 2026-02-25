<?php

namespace App\Livewire\Policy;

use App\Models\Ntb;
use App\Models\NtbUpdate;
use Livewire\Component;
use Livewire\WithPagination;

class NtbManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $description = '';

    public string $product_sector = '';

    public string $barrier_type = '';

    public string $status = 'submitted';

    public function mount(): void
    {
        $this->authorize('policy.view');
    }

    public function openForm(?int $id = null): void
    {
        $this->editingId = $id;

        if ($id) {
            $ntb = Ntb::findOrFail($id);
            $this->title = $ntb->title;
            $this->description = $ntb->description;
            $this->product_sector = $ntb->product_sector ?? '';
            $this->barrier_type = $ntb->barrier_type ?? '';
            $this->status = $ntb->status;
        } else {
            $this->resetFields();
        }

        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->resetFields();
    }

    private function resetFields(): void
    {
        $this->title = '';
        $this->description = '';
        $this->product_sector = '';
        $this->barrier_type = '';
        $this->status = 'submitted';
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'product_sector' => 'nullable|string|max:255',
            'barrier_type' => 'nullable|string|max:255',
            'status' => 'required|in:submitted,escalated,under-review,resolved,closed',
        ]);

        $data = [
            'chapter_id' => auth()->user()->chapter_id,
            'submitter_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'product_sector' => $this->product_sector ?: null,
            'barrier_type' => $this->barrier_type ?: null,
            'status' => $this->status,
        ];

        if ($this->editingId) {
            Ntb::findOrFail($this->editingId)->update($data);
        } else {
            Ntb::create($data);
        }

        $this->closeForm();
    }

    public function addUpdate(int $ntbId, string $note, string $newStatus): void
    {
        $ntb = Ntb::findOrFail($ntbId);

        NtbUpdate::create([
            'ntb_id' => $ntbId,
            'user_id' => auth()->id(),
            'note' => $note,
            'status_changed_to' => $newStatus ?: null,
        ]);

        if ($newStatus) {
            $ntb->update(['status' => $newStatus]);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $ntbs = Ntb::forChapter()
            ->with('submitter')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.policy.ntb-manager', [
            'ntbs' => $ntbs,
        ])->layout('layouts.admin');
    }
}
