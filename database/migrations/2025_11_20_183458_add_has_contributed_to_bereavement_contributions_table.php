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
        Schema::table('bereavement_contributions', function (Blueprint $table) {
            if (!Schema::hasColumn('bereavement_contributions', 'has_contributed')) {
                $table->boolean('has_contributed')->default(false)->after('member_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bereavement_contributions', function (Blueprint $table) {
            if (Schema::hasColumn('bereavement_contributions', 'has_contributed')) {
                $table->dropColumn('has_contributed');
            }
        });
    }
};
