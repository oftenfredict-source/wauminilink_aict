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
            // Drop the old string columns
            $table->dropColumn(['coordinator', 'church_elder']);
            
            // Add new foreign key columns
            $table->unsignedBigInteger('coordinator_id')->nullable()->after('preacher');
            $table->unsignedBigInteger('church_elder_id')->nullable()->after('coordinator_id');
            
            // Add foreign key constraints
            $table->foreign('coordinator_id')->references('id')->on('members')->onDelete('set null');
            $table->foreign('church_elder_id')->references('id')->on('members')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sunday_services', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['coordinator_id']);
            $table->dropForeign(['church_elder_id']);
            
            // Drop the foreign key columns
            $table->dropColumn(['coordinator_id', 'church_elder_id']);
            
            // Restore the old string columns
            $table->string('coordinator')->nullable()->after('preacher');
            $table->string('church_elder')->nullable()->after('coordinator');
        });
    }
};
