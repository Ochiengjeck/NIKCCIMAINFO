<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use App\Notifications\ApplicationApproved;
use App\Notifications\ApplicationRejected;
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
        'chapter_id', 'category_id', 'applicant_name', 'email', 'phone',
        'organization', 'business_profile', 'purpose_of_membership',
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

        if ($stage === 'director-general') {
            $this->notify(new ApplicationApproved($this));
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
