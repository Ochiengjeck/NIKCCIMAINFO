<?php

namespace App\Livewire\Trade;

use App\Models\Investor;
use App\Models\Sector;
use Livewire\Component;
use Livewire\WithPagination;

class InvestorManager extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $company_name = '';

    public string $contact_name = '';

    public string $email = '';

    public string $phone = '';

    public string $sector_id = '';

    public string $investment_range = '';

    public string $status = 'prospecting';

    public string $pipeline_notes = '';

    public function mount(): void
    {
        $this->authorize('trade.view');
    }

    public function openForm(?int $id = null): void
    {
        $this->editingId = $id;

        if ($id) {
            $investor = Investor::findOrFail($id);
            $this->company_name = $investor->company_name;
            $this->contact_name = $investor->contact_name;
            $this->email = $investor->email;
            $this->phone = $investor->phone ?? '';
            $this->sector_id = (string) ($investor->sector_id ?? '');
            $this->investment_range = $investor->investment_range ?? '';
            $this->status = $investor->status;
            $this->pipeline_notes = $investor->pipeline_notes ?? '';
        } else {
            $this->resetFormFields();
        }

        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->resetFormFields();
    }

    private function resetFormFields(): void
    {
        $this->company_name = '';
        $this->contact_name = '';
        $this->email = '';
        $this->phone = '';
        $this->sector_id = '';
        $this->investment_range = '';
        $this->status = 'prospecting';
        $this->pipeline_notes = '';
    }

    public function save(): void
    {
        if ($this->editingId) {
            $this->authorize('trade.edit');
        } else {
            $this->authorize('trade.create');
        }

        $this->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'sector_id' => 'nullable|exists:sectors,id',
            'investment_range' => 'nullable|string|max:255',
            'status' => 'required|in:prospecting,onboarded,active',
            'pipeline_notes' => 'nullable|string',
        ]);

        $data = [
            'chapter_id' => auth()->user()->chapter_id,
            'company_name' => $this->company_name,
            'contact_name' => $this->contact_name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'sector_id' => $this->sector_id ?: null,
            'investment_range' => $this->investment_range ?: null,
            'status' => $this->status,
            'pipeline_notes' => $this->pipeline_notes ?: null,
        ];

        if ($this->editingId) {
            Investor::findOrFail($this->editingId)->update($data);
        } else {
            Investor::create($data);
        }

        $this->closeForm();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $investors = Investor::forChapter()
            ->with('sector')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('company_name', 'like', "%{$this->search}%")
                    ->orWhere('contact_name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(20);

        $sectors = Sector::where('is_active', true)->orderBy('name')->get();

        return view('livewire.trade.investor-manager', [
            'investors' => $investors,
            'sectors' => $sectors,
        ])->layout('layouts.admin');
    }
}
