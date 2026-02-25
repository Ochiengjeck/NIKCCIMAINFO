<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiplomaticEngagement extends Model
{
    use ChapterScoped;

    protected $fillable = [
        'chapter_id', 'ministry_or_body', 'contact_name', 'date',
        'purpose', 'outcome', 'follow_up_date', 'logged_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'follow_up_date' => 'date',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function loggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
