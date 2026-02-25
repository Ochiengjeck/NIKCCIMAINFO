<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeLead extends Model
{
    use ChapterScoped;

    protected $fillable = [
        'chapter_id', 'member_id', 'title', 'description', 'sector_id',
        'type', 'status', 'contact_info', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }
}
