<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diplomatic_engagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->string('ministry_or_body');
            $table->string('contact_name')->nullable();
            $table->date('date');
            $table->text('purpose');
            $table->text('outcome')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->foreignId('logged_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diplomatic_engagements');
    }
};
