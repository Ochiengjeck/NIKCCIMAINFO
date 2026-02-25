<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicalPlatform extends Model
{
    protected $fillable = [
        'name',
        'coordinator_id',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(PlatformInteraction::class, 'platform_id');
    }
}
