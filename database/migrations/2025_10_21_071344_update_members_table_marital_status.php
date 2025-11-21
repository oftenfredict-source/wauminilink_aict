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
        // Check if marital_status column exists, if not add it
        if (!Schema::hasColumn('members', 'marital_status')) {
            Schema::table('members', function (Blueprint $table) {
                $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'separated'])->nullable()->after('profile_picture');
            });
        }
        
        // Drop spouse_alive column if it exists
        if (Schema::hasColumn('members', 'spouse_alive')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('spouse_alive');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Reverse the changes
            $table->dropColumn('marital_status');
            $table->enum('spouse_alive', ['yes', 'no'])->nullable()->after('profile_picture');
        });
    }
};
