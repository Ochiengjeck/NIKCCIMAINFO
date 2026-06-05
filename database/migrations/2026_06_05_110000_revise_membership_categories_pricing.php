<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_categories', function (Blueprint $table) {
            // USD becomes the primary currency; NGN kept as a secondary figure. KES dropped.
            $table->decimal('fee_usd', 12, 2)->nullable()->after('description');

            // Per-group availability + pricing (used only when the corporate/individual toggle is ON).
            $table->boolean('corporate_enabled')->default(false)->after('fee_ngn');
            $table->decimal('corporate_fee_usd', 12, 2)->nullable()->after('corporate_enabled');
            $table->decimal('corporate_fee_ngn', 12, 2)->nullable()->after('corporate_fee_usd');
            $table->boolean('individual_enabled')->default(false)->after('corporate_fee_ngn');
            $table->decimal('individual_fee_usd', 12, 2)->nullable()->after('individual_enabled');
            $table->decimal('individual_fee_ngn', 12, 2)->nullable()->after('individual_fee_usd');
        });

        Schema::table('membership_categories', function (Blueprint $table) {
            $table->dropColumn(['member_type', 'fee_kes']);
        });
    }

    public function down(): void
    {
        Schema::table('membership_categories', function (Blueprint $table) {
            $table->string('member_type')->nullable()->after('slug');
            $table->decimal('fee_kes', 12, 2)->nullable()->after('fee_ngn');
            $table->dropColumn([
                'fee_usd',
                'corporate_enabled', 'corporate_fee_usd', 'corporate_fee_ngn',
                'individual_enabled', 'individual_fee_usd', 'individual_fee_ngn',
            ]);
        });
    }
};
