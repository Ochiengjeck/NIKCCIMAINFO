<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('application_id')->nullable()->after('member_id')
                ->constrained('membership_applications')->nullOnDelete();
            $table->string('currency', 8)->default('USD')->after('member_id');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('application_id');
            $table->dropColumn('currency');
        });
    }
};
