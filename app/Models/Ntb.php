<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ntb extends Model
{
    use ChapterScoped, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'chapter_id', 'submitter_id', 'title', 'description',
        'product_sector', 'barrier_type', 'status',
        'government_engagement_log', 'escalated_at', 'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'government_engagement_log' => 'array',
            'escalated_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitter_id');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(NtbUpdate::class);
    }
}
