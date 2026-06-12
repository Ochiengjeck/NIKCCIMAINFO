<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsArticle extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'news_category_id',
        'excerpt',
        'body',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(NewsTag::class, 'news_article_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(NewsComment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', 'approved')->latest();
    }

    public function featuredImageUrl(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        return Storage::disk('public')->url($this->featured_image);
    }

    /** Estimated reading time in minutes (≈200 words/min). */
    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->body));

        return max(1, (int) ceil($words / 200));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    /** Other published articles sharing this article's category. */
    public function relatedPosts(int $limit = 3): Collection
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->when($this->news_category_id, fn ($q) => $q->where('news_category_id', $this->news_category_id))
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug.'%')->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }
}
