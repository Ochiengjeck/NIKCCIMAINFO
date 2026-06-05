<?php

namespace App\Concerns;

use App\Models\Chapter;

trait ResolvesActingChapter
{
    /**
     * The chapter id to attribute newly-created content to.
     *
     * Chapter users get their own chapter; global users (chapter_id = null,
     * e.g. super-admin / global-secretariat) fall back to the seeded "Global"
     * chapter so NOT NULL chapter_id constraints are satisfied.
     */
    protected function actingChapterId(): ?int
    {
        $chapterId = auth()->user()?->chapter_id;

        if ($chapterId) {
            return $chapterId;
        }

        return Chapter::where('code', 'GLOBAL')->value('id')
            ?? Chapter::orderBy('id')->value('id');
    }
}
