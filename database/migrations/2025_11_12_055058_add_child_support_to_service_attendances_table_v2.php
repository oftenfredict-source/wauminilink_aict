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
        Schema::table('service_attendances', function (Blueprint $table) {
            // Drop the old unique constraint first if it exists
            if (Schema::hasIndex('service_attendances', 'unique_member_service_attendance')) {
                $table->dropUnique('unique_member_service_attendance');
            }
            
            // Make member_id nullable to support children attendance
            $table->unsignedBigInteger('member_id')->nullable()->change();
            
            // Add child_id column for children attendance if it doesn't exist
            if (!Schema::hasColumn('service_attendances', 'child_id')) {
                $table->unsignedBigInteger('child_id')->nullable()->after('member_id');
                
                // Add foreign key constraint for child_id
                $table->foreign('child_id')->references('id')->on('children')->onDelete('cascade');
                
                // Add index for child_id
                $table->index('child_id');
            }
        });
        
        // Add new unique constraints
        Schema::table('service_attendances', function (Blueprint $table) {
            // For member attendance: unique on service_type, service_id, member_id
            if (!Schema::hasIndex('service_attendances', 'unique_member_service_attendance')) {
                $table->unique(['service_type', 'service_id', 'member_id'], 'unique_member_service_attendance');
            }
            
            // For child attendance: unique on service_type, service_id, child_id
            if (!Schema::hasIndex('service_attendances', 'unique_child_service_attendance')) {
                $table->unique(['service_type', 'service_id', 'child_id'], 'unique_child_service_attendance');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_attendances', function (Blueprint $table) {
            // Drop the new unique constraints
            if (Schema::hasIndex('service_attendances', 'unique_child_service_attendance')) {
                $table->dropUnique('unique_child_service_attendance');
            }
            if (Schema::hasIndex('service_attendances', 'unique_member_service_attendance')) {
                $table->dropUnique('unique_member_service_attendance');
            }
            
            // Drop foreign key and index for child_id
            if (Schema::hasColumn('service_attendances', 'child_id')) {
                $table->dropForeign(['child_id']);
                $table->dropIndex(['child_id']);
                
                // Remove child_id column
                $table->dropColumn('child_id');
            }
        });
        
        // Make member_id required again and restore the original unique constraint
        Schema::table('service_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('member_id')->nullable(false)->change();
            if (!Schema::hasIndex('service_attendances', 'unique_member_service_attendance')) {
                $table->unique(['service_type', 'service_id', 'member_id'], 'unique_member_service_attendance');
            }
        });
    }
};
