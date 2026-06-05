<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->after('applicant_name');
            $table->string('address')->nullable()->after('organization');
            $table->string('country')->nullable()->after('address');
            $table->string('website')->nullable()->after('country');
            $table->string('sponsored_by')->nullable()->after('website');
        });
    }

    public function down(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            $table->dropColumn(['contact_person', 'address', 'country', 'website', 'sponsored_by']);
        });
    }
};
