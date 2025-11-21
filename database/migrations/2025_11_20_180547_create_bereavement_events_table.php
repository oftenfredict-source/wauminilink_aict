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
        if (Schema::hasTable('bereavement_events')) {
            return; // Table already exists, skip creation
        }
        
        Schema::create('bereavement_events', function (Blueprint $table) {
            $table->id();
            $table->string('deceased_name'); // Name of deceased or affected family
            $table->text('family_details')->nullable(); // Additional family information
            $table->string('related_departments')->nullable(); // Comma-separated or JSON
            $table->date('incident_date'); // Date of incident
            $table->date('contribution_start_date'); // Start of contribution window
            $table->date('contribution_end_date'); // End of contribution window
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('notes')->nullable();
            $table->text('fund_usage')->nullable(); // Optional: how funds were used
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('closed_at')->nullable(); // When event was closed
            $table->timestamps();
            
            // Indexes for performance
            $table->index('status');
            $table->index('contribution_end_date');
            $table->index('incident_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bereavement_events');
    }
};
