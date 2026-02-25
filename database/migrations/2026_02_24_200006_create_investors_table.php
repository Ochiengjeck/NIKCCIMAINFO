<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->foreignId('sector_id')->nullable()->constrained('sectors')->nullOnDelete();
            $table->string('investment_range')->nullable();
            $table->enum('status', ['prospecting', 'onboarded', 'active'])->default('prospecting');
            $table->json('due_diligence_docs')->nullable();
            $table->text('pipeline_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
