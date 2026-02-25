<?php

namespace App\Livewire\Trade;

use App\Models\B2bMeetingRequest;
use Livewire\Component;
use Livewire\WithPagination;

class B2bMatchmaker extends Component
{
    use WithPagination;

    public function mount(): void
    {
        $this->authorize('trade.view');
    }

    public function render()
    {
        $requests = B2bMeetingRequest::with(['requester', 'target'])
            ->latest()
            ->paginate(20);

        return view('livewire.trade.b2b-matchmaker', [
            'requests' => $requests,
        ])->layout('layouts.admin');
    }
}
