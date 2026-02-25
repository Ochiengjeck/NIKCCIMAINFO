<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatLog extends Model
{
    protected $fillable = [
        'session_id',
        'visitor_ip',
        'messages',
        'escalated_to_user_id',
        'escalated_at',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'messages' => 'array',
            'escalated_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function escalatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalated_to_user_id');
    }
}
