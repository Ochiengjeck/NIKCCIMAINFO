<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            // Explicit names: this table was previously renamed to `blog_posts`, which
            // kept the original `news_articles_*` index/FK names. Re-using the auto
            // names here would collide, so we name them uniquely.
            $table->unique('slug', 'news_articles_slug_unq');

            $table->unsignedBigInteger('news_category_id')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->string('featured_image')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->unsignedBigInteger('author_id');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->foreign('news_category_id', 'news_articles_category_fk')
                ->references('id')->on('news_categories')->nullOnDelete();
            $table->foreign('author_id', 'news_articles_author_fk')
                ->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
