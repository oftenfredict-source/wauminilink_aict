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
        Schema::table('bereavement_events', function (Blueprint $table) {
            // Add family_details if it doesn't exist
            if (!Schema::hasColumn('bereavement_events', 'family_details')) {
                $table->text('family_details')->nullable()->after('deceased_name');
            }
            
            // Add related_departments if it doesn't exist
            if (!Schema::hasColumn('bereavement_events', 'related_departments')) {
                $table->string('related_departments')->nullable()->after('family_details');
            }
            
            // Add fund_usage if it doesn't exist
            if (!Schema::hasColumn('bereavement_events', 'fund_usage')) {
                $table->text('fund_usage')->nullable()->after('notes');
            }
        });
        
        // Copy data from family_name to family_details if family_name exists
        if (Schema::hasColumn('bereavement_events', 'family_name') && Schema::hasColumn('bereavement_events', 'family_details')) {
            DB::statement('UPDATE bereavement_events SET family_details = family_name WHERE family_name IS NOT NULL AND (family_details IS NULL OR family_details = "")');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bereavement_events', function (Blueprint $table) {
            if (Schema::hasColumn('bereavement_events', 'family_details')) {
                $table->dropColumn('family_details');
            }
            if (Schema::hasColumn('bereavement_events', 'related_departments')) {
                $table->dropColumn('related_departments');
            }
            if (Schema::hasColumn('bereavement_events', 'fund_usage')) {
                $table->dropColumn('fund_usage');
            }
        });
    }
};
