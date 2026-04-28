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
        Schema::table('bereavement_contributions', function (Blueprint $table) {
            if (Schema::hasColumn('bereavement_contributions', 'contribution_amount')) {
                $table->renameColumn('contribution_amount', 'amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bereavement_contributions', function (Blueprint $table) {
            if (Schema::hasColumn('bereavement_contributions', 'amount')) {
                $table->renameColumn('amount', 'contribution_amount');
            }
        });
    }
};
