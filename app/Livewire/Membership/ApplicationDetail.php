<?php

namespace App\Livewire\Membership;

use App\Models\FinancialTransaction;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Notifications\PaymentReceived;
use Livewire\Component;

class ApplicationDetail extends Component
{
    public MembershipApplication $application;

    public string $notes = '';

    public string $rejectionReason = '';

    public bool $showRejectModal = false;

    // Payment capture (shown at the payment-pending step)
    public string $paymentMethod = 'cash';

    public string $transactionRef = '';

    public string $paymentAmount = '';

    public string $paymentCurrency = 'USD';

    public string $paymentDate = '';

    public function mount(MembershipApplication $application): void
    {
        $this->authorize('members.view');
        $this->application = $application->load(['chapter', 'category', 'approvals.approver', 'invoice']);

        // Prefill the payment form from the application's resolved fee.
        if ($this->application->chargeUsd() > 0) {
            $this->paymentAmount = (string) $this->application->chargeUsd();
            $this->paymentCurrency = 'USD';
        } elseif ($this->application->chargeNgn() > 0) {
            $this->paymentAmount = (string) $this->application->chargeNgn();
            $this->paymentCurrency = 'NGN';
        }
        $this->paymentDate = now()->format('Y-m-d');
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

        $this->validate([
            'paymentMethod' => 'required|in:cash,bank-transfer,card,mobile-money,cheque,other',
            'transactionRef' => 'nullable|string|max:100',
            'paymentAmount' => 'required|numeric|min:0',
            'paymentCurrency' => 'required|string|max:8',
            'paymentDate' => 'required|date',
        ]);

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

        $amount = (float) $this->paymentAmount;
        $reference = trim($this->transactionRef) !== ''
            ? trim($this->transactionRef)
            : strtoupper($this->paymentMethod).'-APP'.$this->application->id.'-'.now()->format('YmdHis');

        // Record the payment in Finance when there's an amount.
        $transaction = null;
        if ($amount > 0) {
            $transaction = FinancialTransaction::create([
                'chapter_id' => $this->application->chapter_id,
                'member_id' => $member->id,
                'type' => 'membership-fee',
                'amount' => $amount,
                'currency' => $this->paymentCurrency,
                'reference' => $reference,
                'payment_method' => $this->paymentMethod,
                'status' => 'paid',
                'paid_at' => $this->paymentDate,
                'description' => 'Membership payment — '.($this->application->category?->name ?? 'Membership')." (App #{$this->application->id})",
            ]);
        }

        // Persist/settle the invoice so it tracks in Finance.
        $invoice = $this->application->ensureInvoice() ?? $this->application->invoices()->first();
        $invoice?->update([
            'member_id' => $member->id,
            'status' => 'paid',
            'paid_at' => $this->paymentDate,
        ]);

        $this->application->markPaymentReceived();

        if ($transaction) {
            try {
                $this->application->notify(new PaymentReceived($transaction));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $this->application->refresh();

        session()->flash('success', "Payment recorded and membership activated. Number: {$member->membership_number}");
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
