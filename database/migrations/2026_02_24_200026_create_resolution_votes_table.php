<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resolution_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resolution_id')->constrained('board_resolutions')->cascadeOnDelete();
            $table->foreignId('voter_id')->constrained('users');
            $table->enum('vote', ['for', 'against', 'abstain']);
            $table->timestamp('voted_at');
            $table->unique(['resolution_id', 'voter_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resolution_votes');
    }
};
