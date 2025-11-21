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
        Schema::table('members', function (Blueprint $table) {
            $table->string('residence_region')->nullable()->after('address');
            $table->string('residence_district')->nullable()->after('residence_region');
            $table->string('residence_ward')->nullable()->after('residence_district');
            $table->string('residence_street')->nullable()->after('residence_ward');
            $table->string('residence_road')->nullable()->after('residence_street');
            $table->string('residence_house_number')->nullable()->after('residence_road');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'residence_region',
                'residence_district', 
                'residence_ward',
                'residence_street',
                'residence_road',
                'residence_house_number'
            ]);
        });
    }
};
