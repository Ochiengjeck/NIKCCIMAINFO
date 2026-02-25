<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->string('partner_name');
            $table->enum('partner_type', ['government', 'private', 'bilateral']);
            $table->string('title');
            $table->date('signed_at')->nullable();
            $table->date('expiry_at')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['active', 'expired', 'under-negotiation'])->default('under-negotiation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mous');
    }
};
