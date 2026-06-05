<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->text('description')->nullable()->after('category');
            $table->boolean('is_public')->default(false)->after('description');
        });

        Schema::table('policy_briefs', function (Blueprint $table) {
            $table->foreignId('file_media_item_id')->nullable()->after('body')
                ->constrained('media_items')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['description', 'is_public']);
        });

        Schema::table('policy_briefs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('file_media_item_id');
        });
    }
};
