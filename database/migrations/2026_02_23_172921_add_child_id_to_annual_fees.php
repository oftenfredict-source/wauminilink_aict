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
        // Check if member_id is already nullable
        $columns = \DB::select('SHOW COLUMNS FROM annual_fees');
        $memberIdNullable = false;
        $childIdExists = false;
        foreach ($columns as $col) {
            if ($col->Field === 'member_id' && $col->Null === 'YES') {
                $memberIdNullable = true;
            }
            if ($col->Field === 'child_id') {
                $childIdExists = true;
            }
        }

        // Check if unique index exists
        $indices = \DB::select('SHOW INDEX FROM annual_fees');
        $uniqueExists = false;
        foreach ($indices as $idx) {
            if ($idx->Key_name === 'annual_fees_member_id_year_unique') {
                $uniqueExists = true;
            }
        }

        Schema::table('annual_fees', function (Blueprint $table) use ($memberIdNullable, $childIdExists, $uniqueExists) {
            if ($uniqueExists) {
                // To drop unique safely, we might need to drop FK first
                try {
                    $table->dropForeign(['member_id']);
                } catch (\Exception $e) {
                }
                $table->dropUnique(['member_id', 'year']);

                // Re-add FK after dropping unique
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            }

            if (!$memberIdNullable) {
                $table->unsignedBigInteger('member_id')->nullable()->change();
            }

            if (!$childIdExists) {
                $table->foreignId('child_id')->nullable()->after('member_id')->constrained('children')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annual_fees', function (Blueprint $table) {
            if (Schema::hasColumn('annual_fees', 'child_id')) {
                $table->dropForeign(['child_id']);
                $table->dropColumn('child_id');
            }

            try {
                $table->dropForeign(['member_id']);
                $table->unsignedBigInteger('member_id')->nullable(false)->change();
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->unique(['member_id', 'year']);
            } catch (\Exception $e) {
                // Ignore errors on rollback
            }
        });
    }
};
