<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained('technical_platforms')->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('interaction_type');
            $table->text('notes')->nullable();
            $table->timestamp('logged_at');
            $table->foreignId('logged_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_interactions');
    }
};
