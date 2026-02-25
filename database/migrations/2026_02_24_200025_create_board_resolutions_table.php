<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('board_resolutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->string('title');
            $table->text('resolution_text');
            $table->foreignId('moved_by')->constrained('users');
            $table->enum('status', ['pending-vote', 'passed', 'failed'])->default('pending-vote');
            $table->date('voting_deadline')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_resolutions');
    }
};
