<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Deal extends Model
{
    use ChapterScoped, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'chapter_id', 'title', 'sector_id', 'initiator_member_id',
        'counterpart_member_id', 'stage', 'value_usd', 'notes', 'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
            'value_usd' => 'decimal:2',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'initiator_member_id');
    }

    public function counterpart(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'counterpart_member_id');
    }
}
