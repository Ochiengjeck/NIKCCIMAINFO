<?php

namespace App\Livewire\Governance;

use App\Models\Mou;
use Livewire\Component;
use Livewire\WithPagination;

class MouRepository extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $partner_name = '';

    public string $partner_type = 'government';

    public string $title = '';

    public string $signed_at = '';

    public string $expiry_at = '';

    public string $status = 'under-negotiation';

    protected function rules(): array
    {
        return ['partner_name' => 'required|string|max:255', 'partner_type' => 'required|in:government,private,bilateral', 'title' => 'required|string|max:255', 'signed_at' => 'nullable|date', 'expiry_at' => 'nullable|date', 'status' => 'required|in:active,expired,under-negotiation'];
    }

    public function mount(): void
    {
        $this->authorize('governance.view');
    }

    public function save(): void
    {
        $this->authorize('governance.upload');
        $data = $this->validate();
        $data['chapter_id'] = auth()->user()->chapter_id;
        if ($this->editingId) {
            Mou::findOrFail($this->editingId)->update($data);
        } else {
            Mou::create($data);
        }
        $this->reset(['partner_name', 'partner_type', 'title', 'signed_at', 'expiry_at', 'status', 'editingId']);
        $this->showForm = false;
        session()->flash('success', 'MoU saved.');
    }

    public function render()
    {
        return view('livewire.governance.mou-repository', [
            'mous' => Mou::forChapter()->latest()->paginate(15),
        ])->layout('layouts.admin');
    }
}
