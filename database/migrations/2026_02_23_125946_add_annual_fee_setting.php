<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('system_settings')->where('key', 'annual_fee_amount')->delete();
    }
};
