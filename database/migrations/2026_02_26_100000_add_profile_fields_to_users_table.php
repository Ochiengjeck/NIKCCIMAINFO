<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable()->after('remember_token');
            $table->string('phone', 30)->nullable()->after('profile_photo_path');
            $table->string('job_title', 100)->nullable()->after('phone');
            $table->text('bio')->nullable()->after('job_title');
            $table->string('location', 100)->nullable()->after('bio');
            $table->string('timezone', 50)->nullable()->default('UTC')->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_photo_path', 'phone', 'job_title', 'bio', 'location', 'timezone']);
        });
    }
};
