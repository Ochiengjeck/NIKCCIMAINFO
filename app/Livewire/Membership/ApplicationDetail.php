<?php

namespace App\Livewire\Membership;

use App\Models\Member;
use App\Models\MembershipApplication;
use Livewire\Component;

class ApplicationDetail extends Component
{
    public MembershipApplication $application;

    public string $notes = '';

    public string $rejectionReason = '';

    public bool $showRejectModal = false;

    public function mount(MembershipApplication $application): void
    {
        $this->authorize('members.view');
        $this->application = $application->load(['chapter', 'category', 'approvals.approver']);
    }

    public function approve(): void
    {
        $this->authorize('members.approve');

        $stage = $this->resolveStage();
        abort_unless($stage, 403, 'No approval action available at current status.');

        $this->application->approveAtStage($stage, auth()->user(), $this->notes ?: null);
        $this->application->refresh();

        // If final approval + payment pending, auto-create member record
        if ($this->application->status === 'payment-pending') {
            // Member created after payment confirmation
        }

        session()->flash('success', 'Application approved at stage: '.$stage);
        $this->notes = '';
    }

    public function confirmReject(): void
    {
        $this->showRejectModal = true;
    }

    public function reject(): void
    {
        $this->authorize('members.approve');
        $this->validate(['rejectionReason' => 'required|string|min:10']);

        $stage = $this->resolveStage();
        $this->application->reject($this->rejectionReason, auth()->user(), $stage ?? 'membership-officer');
        $this->application->refresh();

        $this->showRejectModal = false;
        $this->rejectionReason = '';
        session()->flash('success', 'Application rejected.');
    }

    public function activateMembership(): void
    {
        $this->authorize('members.approve');

        abort_unless($this->application->status === 'payment-pending', 403);

        $chapter = $this->application->chapter;
        $member = Member::create([
            'chapter_id' => $this->application->chapter_id,
            'category_id' => $this->application->category_id,
            'membership_number' => Member::generateMembershipNumber($chapter->code),
            'first_name' => explode(' ', $this->application->applicant_name, 2)[0],
            'last_name' => explode(' ', $this->application->applicant_name, 2)[1] ?? '',
            'email' => $this->application->email,
            'phone' => $this->application->phone,
            'organization' => $this->application->organization,
            'status' => 'active',
            'joined_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        $this->application->markPaymentReceived();
        $this->application->refresh();

        session()->flash('success', "Membership activated. Number: {$member->membership_number}");
    }

    private function resolveStage(): ?string
    {
        return match ($this->application->status) {
            'pending', 'under-review' => 'membership-officer',
            'chairman-approved' => 'chairman',
            'dg-approved' => 'director-general',
            default => null,
        };
    }

    public function render()
    {
        return view('livewire.membership.application-detail')
            ->layout('layouts.admin');
    }
}
