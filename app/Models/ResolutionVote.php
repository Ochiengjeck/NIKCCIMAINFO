<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResolutionVote extends Model
{
    protected $fillable = ['resolution_id', 'voter_id', 'vote', 'voted_at'];

    protected function casts(): array
    {
        return [
            'voted_at' => 'datetime',
        ];
    }

    public function resolution(): BelongsTo
    {
        return $this->belongsTo(BoardResolution::class, 'resolution_id');
    }

    public function voter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voter_id');
    }
}
