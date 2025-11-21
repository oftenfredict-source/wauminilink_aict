<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the marital_status enum to remove 'single'
        DB::statement("ALTER TABLE members MODIFY COLUMN marital_status ENUM('married', 'divorced', 'widowed', 'separated') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to include 'single' in the enum
        DB::statement("ALTER TABLE members MODIFY COLUMN marital_status ENUM('single', 'married', 'divorced', 'widowed', 'separated') NULL");
    }
};
