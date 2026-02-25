<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->foreignId('organizer_id')->constrained('users');
            $table->string('title');
            $table->enum('type', ['flagship', 'trade-mission', 'sector-forum']);
            $table->text('description')->nullable();
            $table->string('venue')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->integer('max_capacity')->nullable();
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
