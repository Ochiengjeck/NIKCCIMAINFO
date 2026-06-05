<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('gateway_ref');
            // Widen currency from the NGN/KES enum to a free string so USD (now primary) is allowed.
            $table->string('currency', 8)->default('USD')->change();
        });
    }

    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->enum('currency', ['NGN', 'KES'])->default('NGN')->change();
        });
    }
};
