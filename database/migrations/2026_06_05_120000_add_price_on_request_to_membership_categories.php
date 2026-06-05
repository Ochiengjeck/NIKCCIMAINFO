<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_categories', function (Blueprint $table) {
            // When true, public surfaces show "On request" even if a price is set.
            $table->boolean('price_on_request')->default(false)->after('individual_fee_ngn');
        });
    }

    public function down(): void
    {
        Schema::table('membership_categories', function (Blueprint $table) {
            $table->dropColumn('price_on_request');
        });
    }
};
