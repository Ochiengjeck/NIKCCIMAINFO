<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeStatusReview extends Model
{
    use ChapterScoped;

    protected $fillable = [
        'chapter_id', 'period', 'bilateral_trade_data', 'notes', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'bilateral_trade_data' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
