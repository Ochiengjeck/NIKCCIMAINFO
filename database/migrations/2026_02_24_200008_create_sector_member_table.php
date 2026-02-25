<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sector_member', function (Blueprint $table) {
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained('sectors')->cascadeOnDelete();
            $table->primary(['member_id', 'sector_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sector_member');
    }
};
