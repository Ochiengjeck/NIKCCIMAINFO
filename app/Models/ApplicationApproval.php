<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationApproval extends Model
{
    protected $fillable = [
        'application_id', 'approver_id', 'stage', 'action', 'notes', 'actioned_at',
    ];

    protected function casts(): array
    {
        return [
            'actioned_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(MembershipApplication::class, 'application_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
