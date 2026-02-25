<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformInteraction extends Model
{
    protected $fillable = [
        'platform_id',
        'member_id',
        'interaction_type',
        'notes',
        'logged_at',
        'logged_by',
    ];

    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
        ];
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(TechnicalPlatform::class, 'platform_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function logger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
