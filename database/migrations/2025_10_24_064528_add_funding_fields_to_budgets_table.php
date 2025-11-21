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
        Schema::table('budgets', function (Blueprint $table) {
            $table->string('purpose')->nullable()->after('budget_type'); // building, ministry, operations, etc.
            $table->string('primary_offering_type')->nullable()->after('purpose'); // The main offering type this budget should use
            $table->boolean('requires_approval')->default(true)->after('primary_offering_type'); // Whether this budget requires approval for funding allocation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn(['purpose', 'primary_offering_type', 'requires_approval']);
        });
    }
};