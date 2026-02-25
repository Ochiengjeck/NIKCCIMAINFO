<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mou extends Model
{
    use ChapterScoped;

    protected $fillable = [
        'chapter_id', 'partner_name', 'partner_type', 'title',
        'signed_at', 'expiry_at', 'file_path', 'status',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'date',
            'expiry_at' => 'date',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
