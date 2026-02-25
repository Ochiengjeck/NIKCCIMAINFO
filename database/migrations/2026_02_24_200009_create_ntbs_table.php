<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ntbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->foreignId('submitter_id')->constrained('users');
            $table->string('title');
            $table->text('description');
            $table->string('product_sector')->nullable();
            $table->string('barrier_type')->nullable();
            $table->enum('status', ['submitted', 'escalated', 'under-review', 'resolved', 'closed'])->default('submitted');
            $table->json('government_engagement_log')->nullable();
            $table->timestamp('escalated_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ntbs');
    }
};
