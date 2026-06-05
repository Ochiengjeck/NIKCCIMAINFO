<?php

namespace App\Livewire\Policy;

use App\Models\PolicyBrief;
use Livewire\Component;

class PolicyBriefDetail extends Component
{
    public PolicyBrief $brief;

    public function mount(PolicyBrief $brief): void
    {
        $this->authorize('policy.view');
        $this->brief = $brief->load(['author', 'reviewer', 'file']);
    }

    public function publish(): void
    {
        $this->authorize('policy.publish');

        $this->brief->update(['status' => 'published', 'published_at' => now()]);
        $this->brief->refresh();

        session()->flash('success', 'Policy brief published.');
    }

    public function unpublish(): void
    {
        $this->authorize('policy.publish');

        $this->brief->update(['status' => 'in-review', 'published_at' => null]);
        $this->brief->refresh();

        session()->flash('success', 'Policy brief unpublished.');
    }

    public function destroy()
    {
        $this->authorize('policy.delete');

        $this->brief->delete();

        session()->flash('success', 'Policy brief deleted.');

        return $this->redirect(route('admin.policy.briefs'), navigate: true);
    }

    public function render()
    {
        return view('livewire.policy.policy-brief-detail')
            ->layout('layouts.admin');
    }
}
