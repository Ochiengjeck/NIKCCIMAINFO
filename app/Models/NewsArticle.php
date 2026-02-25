<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsArticle extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'category',
        'featured_image',
        'status',
        'author_id',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function featuredImageUrl(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        return Storage::disk('public')->url($this->featured_image);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug.'%')->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }
}
