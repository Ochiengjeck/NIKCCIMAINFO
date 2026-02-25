<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corridors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('lead_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pilot', 'active', 'inactive'])->default('pilot');
            $table->decimal('volume_moved', 14, 2)->default(0);
            $table->integer('firms_onboarded')->default(0);
            $table->decimal('transaction_value_usd', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corridors');
    }
};
