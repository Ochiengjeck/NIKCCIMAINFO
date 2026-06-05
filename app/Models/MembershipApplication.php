<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use App\Notifications\ApplicationApproved;
use App\Notifications\ApplicationRejected;
use App\Notifications\ApplicationStageAdvanced;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MembershipApplication extends Model
{
    use ChapterScoped, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'chapter_id', 'category_id', 'member_group', 'applicant_name', 'contact_person', 'email', 'phone',
        'organization', 'address', 'country', 'website', 'sponsored_by',
        'business_profile', 'purpose_of_membership',
        'declaration_accepted', 'status', 'rejection_reason',
        'submitted_at', 'reviewed_at', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'business_profile' => 'array',
            'declaration_accepted' => 'boolean',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MembershipCategory::class, 'category_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ApplicationApproval::class, 'application_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'application_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'application_id')->latestOfMany();
    }

    /**
     * Create the membership invoice for this application if one is payable and none exists yet.
     * Idempotent — safe to call more than once.
     */
    public function ensureInvoice(): ?Invoice
    {
        if (! $this->hasPayableAmount()) {
            return null;
        }

        if ($existing = $this->invoices()->first()) {
            return $existing;
        }

        $usd = $this->chargeUsd();
        $ngn = $this->chargeNgn();
        [$amount, $currency] = $usd > 0 ? [$usd, 'USD'] : [$ngn, 'NGN'];

        return $this->invoices()->create([
            'chapter_id' => $this->chapter_id,
            'currency' => $currency,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'due_date' => now()->addDays(14),
            'line_items' => [[
                'description' => ($this->category?->name ?? 'Membership').' — annual subscription',
                'qty' => 1,
                'price' => $amount,
            ]],
            'subtotal' => $amount,
            'tax' => 0,
            'total' => $amount,
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /** USD fee for this application, resolved against the chosen group. */
    public function chargeUsd(): float
    {
        return (float) ($this->category?->feeUsd($this->member_group) ?? 0);
    }

    /** NGN fee for this application, resolved against the chosen group. */
    public function chargeNgn(): float
    {
        return (float) ($this->category?->feeNgn($this->member_group) ?? 0);
    }

    public function isPriceOnRequest(): bool
    {
        return (bool) $this->category?->price_on_request;
    }

    /** Whether there is a concrete amount to bill (drives the invoice attachment). */
    public function hasPayableAmount(): bool
    {
        return ! $this->isPriceOnRequest() && ($this->chargeUsd() > 0 || $this->chargeNgn() > 0);
    }

    /** Human charge label — "On request" / "Free" / "$X" (+ " (₦Y)" when NGN set). */
    public function chargeLabel(): string
    {
        if ($this->isPriceOnRequest()) {
            return 'On request';
        }

        $usd = $this->chargeUsd();
        $ngn = $this->chargeNgn();

        if ($usd <= 0 && $ngn <= 0) {
            return 'Free';
        }

        $parts = [];
        if ($usd > 0) {
            $parts[] = '$'.number_format($usd, 2);
        }
        if ($ngn > 0) {
            $parts[] = '₦'.number_format($ngn, 0);
        }

        return count($parts) === 2 ? $parts[0].' ('.$parts[1].')' : $parts[0];
    }

    public function submitForReview(): void
    {
        $this->update([
            'status' => 'under-review',
            'submitted_at' => now(),
        ]);
    }

    public function approveAtStage(string $stage, User $approver, ?string $notes = null): void
    {
        $this->approvals()->create([
            'approver_id' => $approver->id,
            'stage' => $stage,
            'action' => 'approved',
            'notes' => $notes,
            'actioned_at' => now(),
        ]);

        $nextStatus = match ($stage) {
            'membership-officer' => 'chairman-approved',
            'chairman' => 'dg-approved',
            'director-general' => 'payment-pending',
            default => $this->status,
        };

        $updates = ['status' => $nextStatus];

        if ($stage === 'membership-officer') {
            $updates['reviewed_at'] = now();
        }

        if ($stage === 'director-general') {
            $updates['approved_at'] = now();
        }

        $this->update($updates);

        // Email the applicant at every step; never let a mail failure block approval.
        try {
            if ($stage === 'director-general') {
                $this->ensureInvoice();
                $this->notify(new ApplicationApproved($this));
            } else {
                $this->notify(new ApplicationStageAdvanced($this, $stage));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function reject(string $reason, User $approver, string $stage): void
    {
        $this->approvals()->create([
            'approver_id' => $approver->id,
            'stage' => $stage,
            'action' => 'rejected',
            'notes' => $reason,
            'actioned_at' => now(),
        ]);

        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        $this->notify(new ApplicationRejected($this));
    }

    public function markPaymentReceived(): void
    {
        $this->update(['status' => 'active']);
    }

    public function notify($notification): void
    {
        // Route notification to the applicant's email
        \Illuminate\Support\Facades\Notification::route('mail', $this->email)
            ->notify($notification);
    }
}
