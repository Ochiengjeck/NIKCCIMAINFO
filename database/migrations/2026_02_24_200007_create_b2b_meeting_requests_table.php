<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('b2b_meeting_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_member_id')->constrained('members');
            $table->foreignId('target_member_id')->constrained('members');
            $table->text('message');
            $table->date('proposed_date')->nullable();
            $table->enum('status', ['pending', 'accepted', 'declined', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('b2b_meeting_requests');
    }
};
