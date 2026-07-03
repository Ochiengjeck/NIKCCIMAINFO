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

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'blog_category_id',
        'excerpt',
        'body',
        'featured_image',
        'document_path',
        'document_name',
        'document_size',
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
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
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

    public function hasDocument(): bool
    {
        return ! empty($this->document_path);
    }

    public function documentUrl(): ?string
    {
        if (! $this->document_path) {
            return null;
        }

        return Storage::disk('public')->url($this->document_path);
    }

    /** Human-friendly document size (B/KB/MB), or empty string if unknown. */
    public function documentHumanSize(): string
    {
        $bytes = (int) $this->document_size;

        if ($bytes <= 0) {
            return '';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0).' KB';
        }

        return $bytes.' B';
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

    /** Other published posts sharing this post's category. */
    public function relatedPosts(int $limit = 3): Collection
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->when($this->blog_category_id, fn ($q) => $q->where('blog_category_id', $this->blog_category_id))
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
