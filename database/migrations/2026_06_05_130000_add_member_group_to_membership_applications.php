<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            // The corporate/individual group the applicant chose (when grouping is on);
            // null when grouping is off. Used to resolve the correct fee.
            $table->string('member_group')->nullable()->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            $table->dropColumn('member_group');
        });
    }
};
