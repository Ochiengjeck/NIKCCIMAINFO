<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('document_path')->nullable()->after('featured_image');
            $table->string('document_name')->nullable()->after('document_path');
            $table->unsignedBigInteger('document_size')->nullable()->after('document_name');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['document_path', 'document_name', 'document_size']);
        });
    }
};
