<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('featured_image')->nullable()->after('description');
            $table->json('gallery')->nullable()->after('featured_image');
            $table->string('brochure_path')->nullable()->after('gallery');
            $table->string('brochure_name')->nullable()->after('brochure_path');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['featured_image', 'gallery', 'brochure_path', 'brochure_name']);
        });
    }
};
