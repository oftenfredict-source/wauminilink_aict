<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove old single fee setting if exists
        DB::table('system_settings')->where('key', 'annual_fee_amount')->delete();

        // Add adult fee setting
        DB::table('system_settings')->insertOrIgnore([
            'key' => 'annual_fee_adult',
            'value' => '2000',
            'type' => 'integer',
            'category' => 'finance',
            'group' => 'General',
            'description' => 'Annual fee amount for adults (18+ years)',
            'is_editable' => true,
            'is_public' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add child fee setting
        DB::table('system_settings')->insertOrIgnore([
            'key' => 'annual_fee_child',
            'value' => '1000',
            'type' => 'integer',
            'category' => 'finance',
            'group' => 'General',
            'description' => 'Annual fee amount for children (under 18 years)',
            'is_editable' => true,
            'is_public' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('system_settings')->whereIn('key', ['annual_fee_adult', 'annual_fee_child'])->delete();

        // Restore old setting
        DB::table('system_settings')->insertOrIgnore([
            'key' => 'annual_fee_amount',
            'value' => '10000',
            'type' => 'integer',
            'category' => 'finance',
            'group' => 'General',
            'description' => 'The amount members must pay for annual fees',
            'is_editable' => true,
            'is_public' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
