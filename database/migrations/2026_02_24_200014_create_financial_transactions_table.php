<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->enum('type', ['membership-fee', 'renewal', 'event-ticket', 'sponsorship', 'other']);
            $table->decimal('amount', 14, 2);
            $table->enum('currency', ['NGN', 'KES'])->default('NGN');
            $table->string('reference')->unique();
            $table->string('gateway_ref')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
