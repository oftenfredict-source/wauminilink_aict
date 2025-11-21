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
        Schema::create('weekly_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leader_id')->constrained('leaders')->onDelete('cascade');
            $table->date('week_start_date');
            $table->date('week_end_date');
            $table->enum('position', [
                'pastor',
                'assistant_pastor', 
                'secretary',
                'assistant_secretary',
                'treasurer',
                'assistant_treasurer',
                'elder',
                'deacon',
                'deaconess',
                'youth_leader',
                'children_leader',
                'worship_leader',
                'choir_leader',
                'usher_leader',
                'evangelism_leader',
                'prayer_leader',
                'other'
            ]);
            $table->text('duties')->nullable(); // Description of duties/responsibilities
            $table->text('notes')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure no overlapping assignments for the same leader in the same week
            $table->unique(['leader_id', 'week_start_date', 'week_end_date'], 'unique_leader_week');
            
            // Index for quick lookups
            $table->index(['week_start_date', 'week_end_date']);
            $table->index('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_assignments');
    }
};
