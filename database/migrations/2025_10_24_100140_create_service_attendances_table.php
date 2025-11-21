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
        Schema::create('service_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('service_type'); // 'sunday_service' or 'special_event'
            $table->unsignedBigInteger('service_id'); // ID of the service
            $table->unsignedBigInteger('member_id'); // ID of the member who attended
            $table->timestamp('attended_at')->useCurrent(); // When they attended
            $table->string('recorded_by')->nullable(); // Who recorded the attendance
            $table->text('notes')->nullable(); // Optional notes
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['service_type', 'service_id']);
            $table->index('member_id');
            $table->index('attended_at');
            
            // Foreign key constraints
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate attendance records
            $table->unique(['service_type', 'service_id', 'member_id'], 'unique_member_service_attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_attendances');
    }
};
