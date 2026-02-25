<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorridorLog extends Model
{
    protected $fillable = ['corridor_id', 'user_id', 'note', 'logged_at'];

    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
        ];
    }

    public function corridor(): BelongsTo
    {
        return $this->belongsTo(Corridor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
