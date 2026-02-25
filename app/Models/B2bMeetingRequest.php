<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class B2bMeetingRequest extends Model
{
    protected $fillable = [
        'requester_member_id', 'target_member_id',
        'message', 'proposed_date', 'status',
    ];

    protected function casts(): array
    {
        return [
            'proposed_date' => 'date',
        ];
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'requester_member_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'target_member_id');
    }
}
