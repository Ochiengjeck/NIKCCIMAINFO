<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Member extends Model
{
    use ChapterScoped, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'user_id', 'chapter_id', 'membership_number', 'category_id',
        'status', 'first_name', 'last_name', 'email', 'phone', 'organization',
        'export_readiness_score', 'joined_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'date',
            'expires_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MembershipCategory::class, 'category_id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }

    public static function generateMembershipNumber(string $chapterCode): string
    {
        $year = now()->year;
        $count = static::whereHas('chapter', fn ($q) => $q->where('code', $chapterCode))
            ->whereYear('joined_at', $year)
            ->count() + 1;

        return "{$chapterCode}-{$year}-".str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
