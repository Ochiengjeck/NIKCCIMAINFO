<?php

namespace App\Livewire\Membership;

use App\Models\Member;
use Livewire\Component;

class MemberProfile extends Component
{
    public Member $member;

    public bool $editing = false;

    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $phone = '';

    public string $organization = '';

    public string $status = '';

    public function mount(Member $member): void
    {
        $this->authorize('members.view');
        $this->member = $member->load(['chapter', 'category']);
        $this->fillForm();
    }

    public function edit(): void
    {
        $this->authorize('members.edit');
        $this->editing = true;
    }

    public function save(): void
    {
        $this->authorize('members.edit');

        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'organization' => 'nullable|string|max:255',
            'status' => 'required|in:active,suspended,expired',
        ]);

        $this->member->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'organization' => $this->organization,
            'status' => $this->status,
        ]);

        $this->member->refresh();
        $this->editing = false;
        session()->flash('success', 'Member profile updated.');
    }

    public function cancel(): void
    {
        $this->fillForm();
        $this->editing = false;
    }

    private function fillForm(): void
    {
        $this->first_name = $this->member->first_name;
        $this->last_name = $this->member->last_name;
        $this->email = $this->member->email;
        $this->phone = $this->member->phone ?? '';
        $this->organization = $this->member->organization ?? '';
        $this->status = $this->member->status;
    }

    public function render()
    {
        return view('livewire.membership.member-profile')
            ->layout('layouts.admin');
    }
}
