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
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            }
            if (!Schema::hasColumn('expenses', 'pastor_approved_by')) {
                $table->unsignedBigInteger('pastor_approved_by')->nullable()->after('approval_status');
            }
            if (!Schema::hasColumn('expenses', 'pastor_approved_at')) {
                $table->timestamp('pastor_approved_at')->nullable()->after('pastor_approved_by');
            }
            if (!Schema::hasColumn('expenses', 'approval_notes')) {
                $table->text('approval_notes')->nullable()->after('pastor_approved_at');
            }
            if (!Schema::hasColumn('expenses', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('approval_notes');
            }
            
            // Add foreign key constraint if it doesn't exist
            if (!Schema::hasColumn('expenses', 'pastor_approved_by')) {
                $table->foreign('pastor_approved_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['pastor_approved_by']);
            $table->dropColumn([
                'approval_status',
                'pastor_approved_by',
                'pastor_approved_at',
                'approval_notes',
                'rejection_reason'
            ]);
        });
    }
};
