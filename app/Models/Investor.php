<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investor extends Model
{
    use ChapterScoped;

    protected $fillable = [
        'chapter_id', 'company_name', 'contact_name', 'email', 'phone',
        'sector_id', 'investment_range', 'status', 'due_diligence_docs', 'pipeline_notes',
    ];

    protected function casts(): array
    {
        return [
            'due_diligence_docs' => 'array',
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
}
