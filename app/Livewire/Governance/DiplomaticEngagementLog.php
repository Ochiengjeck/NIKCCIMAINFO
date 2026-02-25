<?php

namespace App\Livewire\Governance;

use App\Models\DiplomaticEngagement;
use Livewire\Component;
use Livewire\WithPagination;

class DiplomaticEngagementLog extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public string $ministry_or_body = '';

    public string $contact_name = '';

    public string $date = '';

    public string $purpose = '';

    public string $outcome = '';

    public string $follow_up_date = '';

    protected function rules(): array
    {
        return ['ministry_or_body' => 'required|string|max:255', 'contact_name' => 'nullable|string|max:255', 'date' => 'required|date', 'purpose' => 'required|string', 'outcome' => 'nullable|string', 'follow_up_date' => 'nullable|date'];
    }

    public function mount(): void
    {
        $this->authorize('governance.view');
    }

    public function save(): void
    {
        $this->authorize('governance.upload');
        DiplomaticEngagement::create(array_merge($this->validate(), ['chapter_id' => auth()->user()->chapter_id, 'logged_by' => auth()->id()]));
        $this->reset(['ministry_or_body', 'contact_name', 'date', 'purpose', 'outcome', 'follow_up_date']);
        $this->showForm = false;
        session()->flash('success', 'Engagement logged.');
    }

    public function render()
    {
        return view('livewire.governance.diplomatic-engagement-log', [
            'engagements' => DiplomaticEngagement::forChapter()->with('loggedBy')->latest('date')->paginate(20),
        ])->layout('layouts.admin');
    }
}
