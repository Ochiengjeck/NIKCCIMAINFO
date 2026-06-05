<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Document extends Model
{
    use ChapterScoped, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'chapter_id',
        'title',
        'category',
        'description',
        'is_public',
        'file_path',
        'file_size',
        'mime_type',
        'version',
        'parent_document_id',
        'uploaded_by',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'file_size' => 'integer',
            'is_public' => 'boolean',
        ];
    }

    /**
     * Documents that are approved AND flagged for the public website.
     */
    public function scopePublic($query)
    {
        return $query->where('status', 'approved')->where('is_public', true);
    }

    /**
     * Lower-cased file extension derived from the stored path.
     */
    public function extension(): string
    {
        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }

    /**
     * Human-readable file size (mirrors MediaItem::humanSize()).
     */
    public function humanSize(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes < 1024) {
            return "{$bytes} B";
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 1).' KB';
        }

        return round($bytes / 1048576, 1).' MB';
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'parent_document_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(Document::class, 'parent_document_id');
    }
}
