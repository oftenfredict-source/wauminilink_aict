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
        Schema::table('sunday_services', function (Blueprint $table) {
            $table->string('service_type', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sunday_services', function (Blueprint $table) {
            $table->enum('service_type', [
                'sunday_service',
                'prayer_meeting', 
                'bible_study',
                'youth_service',
                'children_service',
                'women_fellowship',
                'men_fellowship',
                'evangelism',
                'other'
            ])->change();
        });
    }
};
