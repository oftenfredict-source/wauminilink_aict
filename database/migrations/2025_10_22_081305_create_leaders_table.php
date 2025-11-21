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
        Schema::create('leaders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
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
            $table->string('position_title')->nullable(); // For custom positions
            $table->text('description')->nullable();
            $table->date('appointment_date');
            $table->date('end_date')->nullable(); // For term limits
            $table->boolean('is_active')->default(true);
            $table->string('appointed_by')->nullable(); // Who appointed this leader
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Ensure a member can only have one active position of the same type
            $table->unique(['member_id', 'position', 'is_active'], 'unique_active_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaders');
    }
};
