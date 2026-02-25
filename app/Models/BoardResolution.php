<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoardResolution extends Model
{
    use ChapterScoped;

    protected $fillable = [
        'chapter_id', 'title', 'resolution_text', 'moved_by',
        'status', 'voting_deadline', 'file_path',
    ];

    protected function casts(): array
    {
        return [
            'voting_deadline' => 'date',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function movedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moved_by');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ResolutionVote::class, 'resolution_id');
    }

    public function tally(): array
    {
        return [
            'for' => $this->votes()->where('vote', 'for')->count(),
            'against' => $this->votes()->where('vote', 'against')->count(),
            'abstain' => $this->votes()->where('vote', 'abstain')->count(),
        ];
    }
}
