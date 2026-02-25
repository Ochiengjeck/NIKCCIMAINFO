<?php

namespace App\Livewire\Platforms;

use App\Models\PlatformInteraction;
use App\Models\TechnicalPlatform;
use Livewire\Component;

class PlatformDetail extends Component
{
    public TechnicalPlatform $platform;

    public string $interactionType = '';

    public string $notes = '';

    public ?int $memberId = null;

    public function mount(TechnicalPlatform $platform): void
    {
        $this->authorize('settings.view');
        $this->platform = $platform->load(['coordinator', 'interactions.logger', 'interactions.member']);
    }

    public function addInteraction(): void
    {
        $this->validate([
            'interactionType' => 'required|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ]);

        PlatformInteraction::create([
            'platform_id' => $this->platform->id,
            'member_id' => $this->memberId ?: null,
            'interaction_type' => $this->interactionType,
            'notes' => $this->notes ?: null,
            'logged_at' => now(),
            'logged_by' => auth()->id(),
        ]);

        $this->reset(['interactionType', 'notes', 'memberId']);
        $this->platform->refresh()->load(['coordinator', 'interactions.logger', 'interactions.member']);

        session()->flash('success', 'Interaction logged successfully.');
    }

    public function render()
    {
        return view('livewire.platforms.platform-detail', [
            'platform' => $this->platform,
        ])->layout('layouts.admin');
    }
}
