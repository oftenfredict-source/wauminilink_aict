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
        Schema::create('promise_guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number'); // For SMS notifications
            $table->string('email')->nullable(); // Optional
            $table->date('promised_service_date'); // The Sunday they promised to attend
            $table->foreignId('service_id')->nullable()->constrained('sunday_services')->onDelete('set null');
            $table->enum('status', ['pending', 'notified', 'attended', 'cancelled'])->default('pending');
            $table->timestamp('notified_at')->nullable(); // When notification was sent
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('promised_service_date');
            $table->index('status');
            $table->index('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promise_guests');
    }
};




