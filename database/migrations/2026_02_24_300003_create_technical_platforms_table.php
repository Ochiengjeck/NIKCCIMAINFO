<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_platforms');
    }
};
