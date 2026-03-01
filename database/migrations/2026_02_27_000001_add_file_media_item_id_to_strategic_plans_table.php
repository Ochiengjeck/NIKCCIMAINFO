<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('strategic_plans', function (Blueprint $table) {
            $table->foreignId('file_media_item_id')
                ->nullable()
                ->after('file_path')
                ->constrained('media_items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('strategic_plans', function (Blueprint $table) {
            $table->dropForeign(['file_media_item_id']);
            $table->dropColumn('file_media_item_id');
        });
    }
};
