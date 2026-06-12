<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('news_articles', 'blog_posts');
    }

    public function down(): void
    {
        Schema::rename('blog_posts', 'news_articles');
    }
};
