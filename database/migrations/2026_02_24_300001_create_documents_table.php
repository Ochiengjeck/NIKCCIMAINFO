<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();
            $table->string('title');
            $table->enum('category', ['governance', 'mou', 'policy-brief', 'trade-report', 'audit', 'meeting-minutes', 'other']);
            $table->string('file_path');
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('parent_document_id')->nullable()->constrained('documents')->nullOnDelete();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->enum('status', ['draft', 'pending-approval', 'approved', 'archived'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
