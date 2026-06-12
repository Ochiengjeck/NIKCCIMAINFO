<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_article_id')->constrained('news_articles')->cascadeOnDelete();
            $table->string('author_name');
            $table->string('author_email');
            $table->text('body');
            $table->string('status')->default('pending'); // pending, approved, spam
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['news_article_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_comments');
    }
};
