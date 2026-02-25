<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ntb_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ntb_id')->constrained('ntbs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->text('note');
            $table->string('status_changed_to')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ntb_updates');
    }
};
