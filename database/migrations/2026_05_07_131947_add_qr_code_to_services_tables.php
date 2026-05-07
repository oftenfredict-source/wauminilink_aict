<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('sunday_services', 'qr_code')) {
            Schema::table('sunday_services', function (Blueprint $table) {
                $table->string('qr_code')->nullable()->unique()->after('status');
            });
        }

        if (!Schema::hasColumn('special_events', 'qr_code')) {
            Schema::table('special_events', function (Blueprint $table) {
                $table->string('qr_code')->nullable()->unique()->after('venue');
            });
        }
    }

    public function down(): void
    {
        Schema::table('sunday_services', function (Blueprint $table) {
            $table->dropColumn('qr_code');
        });

        Schema::table('special_events', function (Blueprint $table) {
            $table->dropColumn('qr_code');
        });
    }
};
