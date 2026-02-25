<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->string('title');
            $table->foreignId('sector_id')->constrained('sectors');
            $table->foreignId('initiator_member_id')->constrained('members');
            $table->foreignId('counterpart_member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->enum('stage', ['prospect', 'negotiation', 'agreed', 'completed', 'failed'])->default('prospect');
            $table->decimal('value_usd', 14, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
