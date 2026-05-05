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
        Schema::table('pledges', function (Blueprint $table) {
            if (!Schema::hasColumn('pledges', 'pledge_type_other')) {
                $table->string('pledge_type_other', 255)->nullable()->after('pledge_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pledges', function (Blueprint $table) {
            if (Schema::hasColumn('pledges', 'pledge_type_other')) {
                $table->dropColumn('pledge_type_other');
            }
        });
    }
};
