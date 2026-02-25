<?php

namespace App\Livewire\Governance;

use App\Models\BoardResolution;
use App\Models\ResolutionVote;
use Livewire\Component;

class VotingBooth extends Component
{
    public function mount(): void
    {
        $this->authorize('governance.vote');
    }

    public function vote(int $resolutionId, string $vote): void
    {
        $this->authorize('governance.vote');
        abort_unless(in_array($vote, ['for', 'against', 'abstain']), 422);
        $resolution = BoardResolution::findOrFail($resolutionId);
        abort_unless($resolution->status === 'pending-vote', 422, 'Voting closed.');
        ResolutionVote::updateOrCreate(
            ['resolution_id' => $resolutionId, 'voter_id' => auth()->id()],
            ['vote' => $vote, 'voted_at' => now()]
        );
        session()->flash('success', 'Vote recorded: '.ucfirst($vote));
    }

    public function render()
    {
        $resolutions = BoardResolution::forChapter()
            ->where('status', 'pending-vote')
            ->with(['votes' => fn ($q) => $q->where('voter_id', auth()->id())])
            ->latest()->get();

        return view('livewire.governance.voting-booth', compact('resolutions'))->layout('layouts.admin');
    }
}
