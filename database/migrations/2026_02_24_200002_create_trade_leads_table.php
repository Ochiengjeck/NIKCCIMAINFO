<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->foreignId('sector_id')->constrained('sectors');
            $table->enum('type', ['export', 'import', 'partnership']);
            $table->enum('status', ['open', 'matched', 'closed'])->default('open');
            $table->string('contact_info')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_leads');
    }
};
