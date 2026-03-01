<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategicPlan extends Model
{
    use ChapterScoped;

    protected $fillable = [
        'chapter_id', 'title', 'fiscal_year', 'file_path', 'file_media_item_id', 'status', 'uploaded_by',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function mediaItem(): BelongsTo
    {
        return $this->belongsTo(MediaItem::class, 'file_media_item_id');
    }
}
