<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('membership_applications')->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users');
            $table->enum('stage', ['membership-officer', 'chairman', 'director-general']);
            $table->enum('action', ['approved', 'rejected']);
            $table->text('notes')->nullable();
            $table->timestamp('actioned_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_approvals');
    }
};
