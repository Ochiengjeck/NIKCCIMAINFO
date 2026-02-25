<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiSnapshot extends Model
{
    use ChapterScoped;

    protected $fillable = [
        'chapter_id',
        'pillar',
        'metric_key',
        'metric_label',
        'value',
        'period',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'value' => 'decimal:4',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
