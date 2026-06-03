<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_categories', function (Blueprint $table) {
            $table->string('member_type')->nullable()->after('slug');
            $table->decimal('fee_ngn', 12, 2)->nullable()->default(null)->change();
            $table->decimal('fee_kes', 12, 2)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('membership_categories', function (Blueprint $table) {
            $table->dropColumn('member_type');
            $table->decimal('fee_ngn', 12, 2)->default(0)->change();
            $table->decimal('fee_kes', 12, 2)->default(0)->change();
        });
    }
};
