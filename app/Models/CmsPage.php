<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'sections',
        'meta_title',
        'meta_description',
        'is_published',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'sections' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function section(string $key, string $default = ''): string
    {
        return $this->sections[$key] ?? $default;
    }
}
