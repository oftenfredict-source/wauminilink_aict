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
            $table->enum('status', ['scheduled', 'completed'])->default('scheduled')->after('service_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sunday_services', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
