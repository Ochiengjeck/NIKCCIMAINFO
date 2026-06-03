<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use ChapterScoped;

    protected $fillable = [
        'chapter_id', 'organizer_id', 'title', 'type', 'description',
        'featured_image', 'gallery', 'brochure_path', 'brochure_name',
        'inquiry_channels',
        'venue', 'starts_at', 'ends_at', 'max_capacity', 'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'gallery' => 'array',
            'inquiry_channels' => 'array',
        ];
    }

    /**
     * Events visible on the public website — published, ongoing, or completed.
     * Drafts and cancelled events are hidden.
     */
    public function scopePublic($query)
    {
        return $query->whereNotIn('status', ['draft', 'cancelled']);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(EventTicket::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function sponsors(): HasMany
    {
        return $this->hasMany(EventSponsor::class);
    }
}
