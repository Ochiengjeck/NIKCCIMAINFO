<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait ChapterScoped
{
    /**
     * Scope query to the authenticated user's chapter.
     * Super-admins and global-secretariat can see all chapters.
     */
    public function scopeForChapter(Builder $query): Builder
    {
        $user = auth()->user();

        if (! $user || $user->hasRole(['super-admin', 'global-secretariat', 'global-governing-council'])) {
            return $query;
        }

        return $query->where($this->getTable().'.chapter_id', $user->chapter_id);
    }
}
