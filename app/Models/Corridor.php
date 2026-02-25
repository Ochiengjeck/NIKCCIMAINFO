<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Corridor extends Model
{
    protected $fillable = [
        'name', 'lead_id', 'status', 'volume_moved',
        'firms_onboarded', 'transaction_value_usd',
    ];

    protected function casts(): array
    {
        return [
            'volume_moved' => 'decimal:2',
            'transaction_value_usd' => 'decimal:2',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lead_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CorridorLog::class);
    }
}
