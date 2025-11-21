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
        Schema::table('children', function (Blueprint $table) {
            // Drop the foreign key constraint first (try different constraint names)
            try {
                $table->dropForeign(['member_id']);
            } catch (\Exception $e) {
                // Try alternative constraint name
                try {
                    $table->dropForeign('children_member_id_foreign');
                } catch (\Exception $e2) {
                    // Constraint might not exist or have different name, continue
                }
            }
            
            // Make member_id nullable
            $table->unsignedBigInteger('member_id')->nullable()->change();
            
            // Re-add the foreign key constraint (nullable foreign keys are allowed)
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            
            // Add parent/guardian fields for non-member parents
            $table->string('parent_name')->nullable()->after('member_id');
            $table->string('parent_phone')->nullable()->after('parent_name');
            $table->string('parent_relationship')->nullable()->after('parent_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            // Remove the new columns
            $table->dropColumn(['parent_name', 'parent_phone', 'parent_relationship']);
            
            // Make member_id not nullable again (this might fail if there are null values)
            $table->foreignId('member_id')->nullable(false)->change();
        });
    }
};

