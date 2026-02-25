<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained();
            $table->foreignId('category_id')->constrained('membership_categories');
            $table->string('applicant_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organization')->nullable();
            $table->json('business_profile')->nullable();
            $table->text('purpose_of_membership')->nullable();
            $table->boolean('declaration_accepted')->default(false);
            $table->enum('status', [
                'pending',
                'under-review',
                'chairman-approved',
                'dg-approved',
                'payment-pending',
                'active',
                'rejected',
            ])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_applications');
    }
};
