<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('registration_enabled')->default(false)->after('max_capacity');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->string('organisation')->nullable()->after('attendee_email');
            $table->string('designation')->nullable()->after('organisation');
            $table->string('whatsapp_number')->nullable()->after('designation');
            $table->boolean('ooc11_engagement')->default(false)->after('whatsapp_number');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('registration_enabled');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn(['organisation', 'designation', 'whatsapp_number', 'ooc11_engagement']);
        });
    }
};
